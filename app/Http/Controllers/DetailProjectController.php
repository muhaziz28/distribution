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

        $materialPurchases = MaterialPurchases::where('project_id', $id)->get();


        return view('detail-project.index', compact('result', 'materialPurchases'));
    }

    public function materialPurchasesData()
    {

        $result = MaterialPurchases::all();
        return DataTables::of($result)->addIndexColumn()->toJson();
    }
}
