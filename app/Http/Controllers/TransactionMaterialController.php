<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionMaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($projectID)
    {
        return view('transaction-materials.index');
    }
}
