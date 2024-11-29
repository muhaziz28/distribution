<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Project;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
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
        $project = Project::all()->map(function ($project) {
            return [
                'id' => $project->id,
                'tahun_anggaran' => $project->tahun_anggaran,
                'kegiatan' => $project->kegiatan,
                'pekerjaan' => $project->pekerjaan,
                'lokasi' => $project->lokasi,
                'status' => $project->status,
                'detail_url' => route('project.detail', $project->id),
                'can_update' => Gate::allows('update-project', $project),
                'can_delete' => Gate::allows('delete-project', $project),
            ];
        });

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
                'tahun_anggaran'    => 'required|string|max:255',
                'kegiatan'          => 'required|string|max:255',
                'pekerjaan'         => 'required|string|max:255',
                'lokasi'            => 'required|string|max:255',
                'status'            => 'required|string|max:255',
            ], [
                'tahun_anggaran.required'   => 'Tahun Anggaran wajib diisi',
                'kegiatan.required'         => 'Kegiatan wajib diisi',
                'pekerjaan.required'        => 'Pekerjaan wajib diisi',
                'lokasi.required'           => 'Lokasi wajib diisi',
                'status.required'           => 'Status wajib diisi',
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
                'message' => 'Project ' . $project->kegiatan . ' berhasil ditambahkan',
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
        Log::info($request->all());
        if (!Gate::allows('update-project')) {
            return response()->json([
                'success' => false,
                'message' => 'You are unauthorized to access this resource',
            ]);
        }
        try {
            $validate = Validator::make($request->all(), [
                'tahun_anggaran'    => 'required|string|max:255',
                'kegiatan'          => 'required|string|max:255',
                'pekerjaan'         => 'required|string|max:255',
                'lokasi'            => 'required|string|max:255',
                'status'            => 'required|string|max:255',
            ], [
                'tahun_anggaran.required'   => 'Tahun Anggaran wajib diisi',
                'kegiatan.required'         => 'Kegiatan wajib diisi',
                'pekerjaan.required'        => 'Pekerjaan wajib diisi',
                'lokasi.required'           => 'Lokasi wajib diisi',
                'status.required'           => 'Status wajib diisi',
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
                'message' => 'Project ' . $project->kegiatan . ' berhasil diupdate',
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
                'message' => 'Project ' . $project->kegiatan . ' berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
