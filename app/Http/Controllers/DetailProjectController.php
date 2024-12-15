<?php

namespace App\Http\Controllers;

use App\Models\MaterialPurchases;
use App\Models\Project;
use App\Models\Tukang;
use App\Models\WorkerProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables as DataTablesDataTables;
use Yajra\DataTables\Facades\DataTables;

class DetailProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id)
    {
        $result = Project::with(['workerProjects.tukang'])->where('id', $id)->first();

        if (!$result) return redirect()->route('project.index')->with('error', 'Project not found.');

        return view('detail-project.index', compact('result'));
    }

    public function workerAssignmentData($projectID)
    {
        $result = WorkerProject::with('tukang', 'project')->where('project_id', $projectID)->get();

        return DataTablesDataTables::of($result)->addIndexColumn()->toJson();
    }

    public function materialPurchasesData()
    {

        $result = MaterialPurchases::all();
        return DataTables::of($result)->addIndexColumn()->toJson();
    }

    public function addDetail(Request $request, $id)
    {
        if (!Gate::allows('create-project')) {
            return response()->json([
                'success' => false,
                'message' => 'You are unauthorized to access this resource',
            ]);
        }

        // Validasi
        $validate = Validator::make($request->all(), [
            'worker_id' => 'required|array',
            'worker_id.*' => 'exists:tukang,id',
            'join_date' => 'required'
        ], [
            'worker_id.required' => 'Tukang harus di pilih.',
            'worker_id.*.exists' => 'Tukang yang dipilih tidak valid',
            'join_date'          => 'Tanggal mulai kerja tidak boleh kosong',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors()->first()
            ]);
        }

        try {
            $project = Project::find($id);
            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Project not found',
                ]);
            }

            foreach ($request->worker_id as $workerId) {
                WorkerProject::create([
                    'worker_id' => $workerId,
                    'project_id' => $project->id,
                    'join_date' => $request->join_date,
                ]);
            }
            return response()->json([
                'success' => true,
                'message' => 'Tukang' . $project->kegiatan . ' berhasil ditambahkan',
                'data' => $project
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function hapusDetail(Request $request)
    {
        if (!Gate::allows('delete-project')) {
            return response()->json([
                'success' => false,
                'message' => 'You are unauthorized to access this resource',
            ]);
        }

        try {
            $workerProject = WorkerProject::find($request->id);

            $workerProject->delete();
            return response()->json([
                'success' => true,
                'message' => 'Tukang berhasil dihapus dari project.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}
