<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use App\Models\WorkerDetailPayment;
use App\Models\WorkerPayment;
use Exception;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class WorkerPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function data($blockID)
    {
        $data = WorkerPayment::where("block_id", $blockID)->with(['workerDetailPayments'])->orderBy('week', 'desc')->get()->map(function ($payment) {
            $totalPayment = $payment->workerDetailPayments->reduce(function ($total, $detail) {
                return $total + (($detail->upah ?? 0) - ($detail->pinjaman ?? 0));
            }, 0);

            $payment->total = $totalPayment;

            if ($payment->attachment) {
                $payment->attachment_url = asset('storage/' . $payment->attachment);
            } else {
                $payment->attachment_url = null;
            }

            return $payment;
        });

        return DataTables::of($data)->addIndexColumn()->toJson();
    }

    public function add($blockID)
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        $activity = Activities::with([
            'workerGroups.tukang',
            'workerGroups.workerAttendances' => function ($query) use ($startOfWeek, $endOfWeek) {
                $query->whereBetween('tanggal', [$startOfWeek, $endOfWeek]);
            },
        ])->get();

        return view('worker-payment.index', compact('activity', 'blockID'));
    }

    public function destroy(Request $request)
    {
        try {
            $result = WorkerPayment::findOrFail($request->id);
            if (Storage::disk('public')->exists($result->attachment)) {
                Storage::disk('public')->delete($result->attachment);
            }
            $result->delete();

            return response()->json([
                'success' => true,
                'message' => 'Bukti pembayaran minggu ke ' . $result->week . ' berhasil dihapus',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success'   => false,
                'message'   => $e->getMessage(),
            ]);
        }
    }

    public function store(Request $request)
    {
        try {


            $validator = Validator::make($request->all(), [
                'block_id' => 'required|integer',
                'week' => 'required|integer',
                'payment_date' => 'required|date_format:d/m/Y',
                'file' => 'nullable|string',
                'payments' => 'required|array',
                'payments.*.worker_group_id' => 'required|integer',
                'payments.*.upah' => 'required|numeric|min:0',
                'payments.*.pinjaman' => 'nullable|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }

            $finalFilePath = null;
            if ($request->file) {
                $relativeFilePath = 'uploads/worker-payment/' . uniqid() . '_' . basename($request->file);
                $finalFilePath = storage_path('app/public/' . $relativeFilePath);

                if (!file_exists(dirname($finalFilePath))) {
                    mkdir(dirname($finalFilePath), 0777, true);
                }

                $tmpFilePath = storage_path('app/' . $request->file);
                if (file_exists($tmpFilePath)) {
                    rename($tmpFilePath, $finalFilePath);
                    $finalFilePath = $relativeFilePath;
                } else {
                    throw new Exception("File tidak ditemukan di direktori sementara.");
                }
            }

            $workerPayment = WorkerPayment::create([
                'block_id' => $request->block_id,
                'week' => $request->week,
                'payment_date' => \Carbon\Carbon::createFromFormat('d/m/Y', $request->payment_date)->format('Y-m-d'),
                'attachment' => $finalFilePath,
            ]);

            foreach ($request->payments as $payment) {
                WorkerDetailPayment::create([
                    'worker_group_id' => $payment['worker_group_id'],
                    'worker_payment_id' => $workerPayment->id,
                    'upah' => $payment['upah'],
                    'pinjaman' => $payment['pinjaman'] ?? 0,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => "Data berhasil ditambahkan",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
