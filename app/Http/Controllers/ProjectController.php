<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Project;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;


class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        return view('project.index');
    }
    public function data()
    {
        if (!Gate::allows('read-project')) {
            return response()->json([
                'success' => false,
                'message' => 'You are unauthorized to access this resource',
            ]);
        }
        $project = Project::all();

        return DataTables::of($project)->addIndexColumn()->toJson();
    }


    public function store(Request $request)
    {
        if (!Gate::allows('create-project')) {
            return response()->json([
                'success' => false,
                'message' => 'You are unauthorized to access this resource',
            ]);
        }
        try {
            $validate = Validator::make($request->all(), [
                'tahun_anggaran' => 'required|string|max:255',
                'kegiatan' => 'required|string|max:255',
                'pekerjaan' => 'required|string|max:255',
                'lokasi' => 'required|string|max:255',
                'status' => 'required|string|max:255',
            ], [
                'tahun_anggaran.required' => 'The Tahun Anggaran field is required',
                'kegiatan.required' => 'The Kegiatan field is required',
                'pekerjaan.required' => 'The Pekerjaan field is required',
                'lokasi.required' => 'The Lokasi field is required',
                'status.required' => 'The Status field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validate->errors()->first()
                ]);
            }

            $project = Project::create([
                'tahun_anggaran' => $request->tahun_anggaran,
                'kegiatan' => $request->kegiatan,
                'pekerjaan' => $request->pekerjaan,
                'lokasi' => $request->lokasi,
                'status' => $request->status,
            ]);


            return response()->json([
                'success' => true,
                'message' => 'Project ' . $project->tahun_anggaran . ' has been created',
                'data' => $project
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    public function update(Request $request)
    {
        if (!Gate::allows('update-project')) {
            return response()->json([
                'success' => false,
                'message' => 'You are unauthorized to access this resource',
            ]);
        }
        try {
            $validate = Validator::make($request->all(), [
                'tahun_anggaran' => 'required|string|max:255',
                'kegiatan' => 'required|string|max:255',
                'pekerjaan' => 'required|string|max:255',
                'lokasi' => 'required|string|max:255',
                'status' => 'required|string|max:255',
            ], [
                'tahun_anggaran.required' => 'The Tahun Anggaran field is required',
                'kegiatan.required' => 'The Kegiatan field is required',
                'pekerjaan.required' => 'The Pekerjaan field is required',
                'lokasi.required' => 'The Lokasi field is required',
                'status.required' => 'The Status field is required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validate->errors()->first()
                ]);
            }

            $project = Project::find($request->id);
            $project->tahun_anggaran = $request->tahun_anggaran;
            $project->kegiatan = $request->kegiatan;
            $project->pekerjaan = $request->pekerjaan;
            $project->lokasi = $request->lokasi;
            $project->status = $request->status;

            $project->save();

            return response()->json([
                'success' => true,
                'message' => 'Project ' . $project->tahun_anggaran . ' has been updated',
                'data' => $project
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    public function destroy(Request $request)
    {
        if (!Gate::allows('delete-project')) {
            return response()->json([
                'success' => false,
                'message' => 'You are unauthorized to access this resource',
            ]);
        }
        try {
            $project = Project::find($request->id);
            $project->delete();

            return response()->json([
                'success' => true,
                'message' => 'Project ' . $project->tahun_anggaran . ' has been deleted'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
