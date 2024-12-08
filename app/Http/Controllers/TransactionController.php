<?php

namespace App\Http\Controllers;

use App\Models\MaterialPurchases;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('transaction.index');
    }

    public function data()
    {
        $purchase = MaterialPurchases::with('materialPurchaseItems', 'vendor')->get();
        $result = $purchase->map(function ($purchases) {
            $total = $purchases->materialPurchaseItems->sum('total');

            $purchases->total = $total;
            unset($purchases->materialPurchaseItems);
            return $purchases;
        });

        return DataTables::of($result)->addIndexColumn()->toJson();
    }
}
