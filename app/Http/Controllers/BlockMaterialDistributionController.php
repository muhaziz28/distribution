<?php

namespace App\Http\Controllers;

use App\Models\BlockMaterialDistribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class BlockMaterialDistributionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function data(Request $request, $blockID)
    {
        Log::info($request->date);
        $query = BlockMaterialDistribution::with('material.bahan.satuan', 'material.materialPurchaseItem')
            ->where('block_id', $blockID);

        if ($request->has('date') && !empty($request->date)) {
            $dates = explode(' - ', $request->date);

            if (count($dates) === 2) {
                $startDate = trim($dates[0]);
                $endDate = trim($dates[1]);

                try {
                    $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', $startDate)->startOfDay();
                    $endDate = \Carbon\Carbon::createFromFormat('d/m/Y', $endDate)->endOfDay();

                    $query->whereBetween('distribution_date', [$startDate, $endDate]);
                } catch (\Exception $e) {
                    Log::error('Error parsing date: ' . $e->getMessage());
                    return response()->json(['message' => 'Invalid date format.'], 422);
                }
            }
        }

        if ($request->has('vendor') && !empty($request->vendor)) {
            $query->whereHas('material.materialPurchaseItem.vendor', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->vendor . '%');
            });
        }

        $data = $query->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->toJson();
    }
}
