<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialUpdateLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($materialID)
    {
        $material = Material::with("materialLogs", "bahan")->find($materialID);

        return view('log.index', compact('material'));
    }
}
