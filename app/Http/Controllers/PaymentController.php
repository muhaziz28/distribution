<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Payment;
use App\Models\PaymentAdditionalItems;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function data($blockID)
    {
        $query = Payment::where("block_id", $blockID)->get();
        $data = $query->map(function ($payment) {
            return [
                'id' => $payment->id,
                'payment_date' => $payment->payment_date,
                'payment_type' => $payment->payment_type,
                'total'        => $payment->total,
                'attachment'   => $payment->attachment != null ? url(asset('storage/' . $payment->attachment)) : null,
            ];
        });

        return DataTables::of($data)->addIndexColumn()->toJson();
    }

    public function store(Request $request, $blockID)
    {
        try {
            $paymentJson = $request->input('payment');
            $filePath = $request->input('file');

            $payment = json_decode($paymentJson, true);
            if ($payment['payment_type'] == "dp") {
                $paymentCheck = Payment::where("block_id", $blockID)->where("payment_type", "dp")->first();
                if ($paymentCheck) {
                    return response()->json([
                        "success" => false,
                        "message" => "Tidak dapat menambahkan data pembayaran, jenis pembayaran DP sudah ditambahkan sebelumnya"
                    ]);
                }
            }

            $validator = Validator::make([
                'payment' => $payment,
                'file' => $filePath,
            ], [
                'payment.payment_date'         => 'required',
                'payment.payment_type'         => 'required',
                'payment.total'                => 'required',
                'payment.note'                 => 'nullable',
                'file'                         => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid.',
                    'errors' => $validator->errors(),
                ], 400);
            }

            DB::beginTransaction();
            $finalFilePath = null;

            if ($filePath) {
                $relativeFilePath = 'uploads/payment/' . basename($filePath);

                $finalFilePath = storage_path('app/public/' . $relativeFilePath);

                if (!file_exists(dirname($finalFilePath))) mkdir(dirname($finalFilePath), 0777, true);

                $tmpFilePath = storage_path('app/' . $filePath);
                if (file_exists($tmpFilePath)) {
                    rename($tmpFilePath, $finalFilePath);
                } else {
                    throw new Exception("File tidak ditemukan di direktori sementara.");
                }

                $relativeFilePathUrl =  $relativeFilePath;
            }

            Payment::create([
                'block_id'          => $blockID,
                'payment_type'      => $payment['payment_type'],
                'payment_date'      => \Carbon\Carbon::createFromFormat('d/m/Y', $payment['payment_date'])->format('Y-m-d'),
                'total'             => $payment['total'],
                'note'              => $payment['note'],
                'attachment'        => $relativeFilePathUrl ?? null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function additionalItemStore(Request $request)
    {
        Log::info('Request Data:', $request->all());
        $itemsJson = $request->input('items');
        $payment = $request->input('paymentDate');
        $filePath = $request->input('file');
        $blockID = $request->input('blockID');

        $items = json_decode($itemsJson, true);

        $validator = Validator::make([
            'items'         => $items,
            'payment_date'   => $payment,
            'file'          => $filePath,
        ], [
            'payment_date'              => 'required',
            'items'                     => 'required|array',
            'items.*.item_name'         => 'required',
            'items.*.item_description'  => 'nullable',
            'items.*.total'             => 'required|numeric|min:0',
            'file' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors'  => $validator->errors(),
            ], 400);
        }
        DB::beginTransaction();
        try {
            $finalFilePath = null;

            if ($filePath) {
                $relativeFilePath = 'uploads/payment/' . basename($filePath);

                $finalFilePath = storage_path('app/public/' . $relativeFilePath);

                if (!file_exists(dirname($finalFilePath))) {
                    mkdir(dirname($finalFilePath), 0777, true);
                }

                $tmpFilePath = storage_path('app/' . $filePath);
                if (file_exists($tmpFilePath)) {
                    rename($tmpFilePath, $finalFilePath);
                } else {
                    throw new Exception("File tidak ditemukan di direktori sementara.");
                }

                $relativeFilePathUrl = $relativeFilePath;
            }

            $total = 0;
            foreach ($items as $item) {
                $total += $item['total'];
            }

            $payment = Payment::create([
                'block_id'          => $blockID,
                'payment_type'      => "item_payment",
                'payment_date'      => \Carbon\Carbon::createFromFormat('d/m/Y', $payment)->format('Y-m-d'),
                'total'             => $total,
                'note'              => "Pembayaran tambahan untuk item",
                'attachment'        => $relativeFilePathUrl ?? null,
            ]);

            foreach ($items as $item) {
                PaymentAdditionalItems::create([
                    "block_id"          => $blockID,
                    "payment_id"        => $payment->id,
                    "item_name"         => $item['item_name'],
                    "item_description"  => $item['item_description'],
                    "total"             => $item['total'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "ok"
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $payment = Payment::find($request->id);
            if (Storage::disk('public')->exists($payment->attachment)) {
                Storage::disk('public')->delete($payment->attachment);
            }
            $payment->delete();

            return response()->json([
                "success" => false,
                "message" => "Data berhasil dihapus"
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function additionalItem($blockID)
    {
        $block = Block::find($blockID);
        return view("block.additional-item", compact("block"));
    }
}
