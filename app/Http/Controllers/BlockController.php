<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

use function PHPUnit\Framework\returnSelf;

class BlockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function data($projectID)
    {
        $result = Block::with('customer')->where("project_id", $projectID)->get()->map(function ($result) {
            return [
                'id'                => $result->id,
                'project_id'        => $result->project_id,
                'block'             => $result->block,
                'type'              => $result->type,
                'harga'             => $result->harga,
                'luas_tanah'        => $result->luas_tanah,
                'luas_bangunan'     => $result->luas_bangunan,
                'customer_id'       => $result->customer_id,
                'customer'          => $result->customer,
                'detail_url'        => route('block.detail', $result->id),
            ];
        });
        return DataTables::of($result)->addIndexColumn()->toJson();
    }

    public function store(Request $request, $projectID)
    {
        try {
            $validationResponse = $this->validateRequest($request);
            if ($validationResponse) return $validationResponse;

            $result = Block::create([
                'project_id'  => $projectID,
                'block'       => $request->block,
                'type'        => $request->type,
                'customer_id' => $request->customer_id,
                'harga'       => $request->harga,
                'luas_tanah'  => $request->luas_tanah,
                'luas_bangunan' => $request->luas_bangunan,
            ]);
            $message = $result->block . ' berhasil ditambahkan.';

            return response()->json(['success' => true, 'message' => $message], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function update(Request $request)
    {
        try {
            $validationResponse = $this->validateRequest($request);
            if ($validationResponse) return $validationResponse;

            $block = Block::findOrFail($request->id);
            $block->update([
                'block'       => $request->block,
                'type'        => $request->type,
                'customer_id' => $request->customer_id,
                'harga'       => $request->harga,
                'luas_tanah'  => $request->luas_tanah,
                'luas_bangunan' => $request->luas_bangunan,
            ]);

            return response()->json([
                'success' => true,
                'message' => $block->block . ' berhasil diupdate'
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
        try {
            $result = Block::find($request->id);
            $result->delete();

            return response()->json([
                'success' => true,
                'message' => $result->block . ' berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function detail($id)
    {
        try {
            $result = Block::findOrFail($id);
            return view('block.index', compact('result'));
        } catch (Exception $e) {
            return redirect()->back();
        }
    }

    private function validateRequest($request)
    {
        $validate = Validator::make($request->all(), [
            'block'       => 'required',
            'type'        => 'required',
        ], [
            'block.required'       => 'Blok wajib diisi',
            'type.required'        => 'Type wajib diisi',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors()->first()
            ]);
        }

        return null;
    }
}
