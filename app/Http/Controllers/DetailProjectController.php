<?php

namespace App\Http\Controllers;

use App\Models\MaterialPurchases;
use App\Models\Project;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DetailProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id)
    {
        $result = Project::findOrFail($id);


        $materialPurchases = MaterialPurchases::with('materialPurchaseItems')->get();

        foreach ($materialPurchases as $materialPurchase) {
            $materialPurchase->total = $materialPurchase->materialPurchaseItems->sum(function ($item) {
                return $item->qty * $item->harga_satuan;
            });
        }

        $total = $materialPurchases->sum('total');

        return view('detail-project.index', compact('result', 'materialPurchases', 'total'));
    }

    public function materialPurchasesData()
    {

        $result = MaterialPurchases::all();
        return DataTables::of($result)->addIndexColumn()->toJson();
    }
}
