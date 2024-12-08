<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialPurchaseItems;
use App\Models\MaterialPurchases;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionMaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('transaction-materials.index');
    }

    public function store(Request $request)
    {
        $purchaseJson = $request->input('purchase');
        $belanjaJson = $request->input('belanja');
        $filePath = $request->input('file');

        $purchase = json_decode($purchaseJson, true);
        $belanja = json_decode($belanjaJson, true);

        $validator = Validator::make([
            'purchase' => $purchase,
            'belanja' => $belanja,
            'file' => $filePath,
        ], [
            'purchase.vendor_id' => 'required|exists:vendors,id',
            'purchase.transaction_date' => 'required',
            'belanja' => 'required|array',
            'belanja.*.bahan_id' => 'required|exists:bahans,id',
            'belanja.*.qty' => 'required|numeric|min:1',
            'belanja.*.harga_satuan' => 'required|numeric|min:0',
            'belanja.*.total' => 'required|numeric|min:0',
            'file' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid.',
                'errors' => $validator->errors(),
            ], 400);
        }

        DB::beginTransaction();
        try {
            $finalFilePath = null;

            if ($filePath) {
                $relativeFilePath = 'uploads/material_purchases/' . basename($filePath);

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

                $relativeFilePathUrl = asset('storage/' . $relativeFilePath);
            }

            $materialPurchase = MaterialPurchases::create([
                'vendor_id'         => $purchase['vendor_id'],
                'transaction_date'  => $purchase['transaction_date'],
                'attachment'        => $relativeFilePathUrl ?? null,
            ]);

            foreach ($belanja as $item) {
                $materialPurchaseItem = MaterialPurchaseItems::create([
                    'material_purchases_id' => $materialPurchase->id,
                    'bahan_id' => $item['bahan_id'],
                    'qty' => $item['qty'],
                    'harga_satuan' => $item['harga_satuan'],
                    'total' => $item['total'],
                ]);
                $vendorID = $purchase['vendor_id'];
                $bahanID = $item['bahan_id'];
                $qty = $item['qty'];

                $material = Material::where('vendor_id', $vendorID)->where('bahan_id', $bahanID)->first();
                if ($material) {
                    $material->qty += $qty;
                    $material->save();
                } else {
                    Material::create([
                        'vendor_id' => $vendorID,
                        'bahan_id' => $bahanID,
                        'qty' => $qty,
                    ]);
                }
            }


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


    private function rmdir_recursive($dir)
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $filePath = $dir . DIRECTORY_SEPARATOR . $file;
            if (is_dir($filePath)) {
                $this->rmdir_recursive($filePath);
            } else {
                unlink($filePath);
            }
        }

        rmdir($dir);
    }

    public function detailTransaction($materialPurchaseID)
    {
        $result = MaterialPurchases::with('materialPurchaseItems.bahan')->find($materialPurchaseID);
        foreach ($result->materialPurchaseItems as $materialPurchaseItem) {
            $materialPurchaseItem->total = $materialPurchaseItem->qty * $materialPurchaseItem->harga_satuan;
        }
        $total = $result->materialPurchaseItems->sum('total');
        return view('detail-project.detail-transaction', compact('result', 'total'));
    }
}
