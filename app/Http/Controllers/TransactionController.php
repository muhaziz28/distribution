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

	$data = $purchase->map(function ($purchase) {
		if($purchase->attachment){
			$purchase->attachment_url = asset('storage/'. $purchase->attachment);
		}else {
			$purchase->attachment_url = null;
		}
            return [
                'id'                => $purchase->id,
                'vendor_id'         => $purchase->vendor_id,
                'attachment'        => $purchase->attachment,
		'attachment_url'    => $purchase->attachment_url,
		'transaction_date'  => $purchase->transaction_date,
                'total'             => $purchase->total,
                'vendor'            => $purchase->vendor,
                'detail_url'        => route('transaction-materials.detailTransaction', $purchase->id),
            ];
        });

        return DataTables::of($data)->addIndexColumn()->toJson();
    }
}
