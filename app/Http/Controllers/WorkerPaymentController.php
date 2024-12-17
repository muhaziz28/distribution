<?php

namespace App\Http\Controllers;

use App\Models\WorkerPayment;
use Exception;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class WorkerPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function data($projectID)
    {
        $result = WorkerPayment::with('project')->where("project_id", $projectID)->get();

        return DataTables::of($result)->addIndexColumn()->toJson();
    }

    public function destroy(Request $request)
    {
        try {
            $result = WorkerPayment::findOrFail($request->id);
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
            $validated = $request->validate([
                'file' => 'nullable|string',
            ]);
            Log::info($request->all());
            $filePath = $request->input('attachment');

            $finalFilePath = null;
            $relativeFilePathUrl = null;
            if ($filePath) {
                $relativeFilePath = 'uploads/worker_payments/' . basename($filePath);

                $finalFilePath = storage_path('app/public/' . $relativeFilePath);

                if (!file_exists(dirname($finalFilePath))) {
                    mkdir(dirname($finalFilePath), 0777, true);
                }

                $tmpFilePath = storage_path('app/' . $filePath);

                if (file_exists($tmpFilePath)) {
                    rename($tmpFilePath, $finalFilePath);
                } else {
                    throw new \Exception("File tidak ditemukan di direktori sementara.");
                }

                $relativeFilePathUrl = $relativeFilePath;
            }

            $result = new WorkerPayment();
            $result->transaction_date = $request->transaction_date;
            $result->project_id = $request->project_id;
            $result->total = $request->total;
            $result->week = $request->week;
            $result->attachment = $relativeFilePathUrl;
            $result->save();

            Storage::deleteDirectory('tmp');


            return response()->json([
                'success' => true,
                'message' => 'File berhasil disimpan.',
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                "success" => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
