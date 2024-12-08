<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Gate::allows('read-customer')) return view('customer.index');
        return view('error.unauthorize');
    }

    public function data(Request $request)
    {
        if (Gate::allows('read-customer')) {
            $result = Customer::search($request->search)->get();

            return DataTables::of($result)->addIndexColumn()->toJson();
        } else {
            return response()->json([
                'success' => false,
                'message' => 'You are unauthorized to access this resource'
            ]);
        }
    }

    public function store(Request $request)
    {
        if (!Gate::allows('create-customer')) {
            return response()->json([
                'success' => false,
                'message' => 'You are unauthorized to access this resource'
            ]);
        }

        try {
            $validationResponse = $this->validateRequest($request);
            if ($validationResponse) return $validationResponse;

            $result = Customer::create([
                'name'        => $request->name,
                'no_hp'       => $request->no_hp,
            ]);
            $message = 'Customer ' . $result->name . ' berhasil ditambahkan.';

            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function update(Request $request)
    {
        if (Gate::allows('update-customer')) {
            try {
                $validationResponse = $this->validateRequest($request);
                if ($validationResponse) return $validationResponse;

                $customer = Customer::findOrFail($request->id);
                $customer->update([
                    'name'        => $request->name,
                    'no_hp'       => $request->no_hp,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => $customer->name . ' berhasil diupdate'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'You are unauthorized to access this resource'
            ]);
        }
    }

    public function destroy(Request $request)
    {
        if (Gate::allows('delete-customer')) {
            try {
                $result = Customer::find($request->id);
                $result->delete();

                return response()->json([
                    'success' => true,
                    'message' => $result->name . ' berhasil dihapus'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'You are unauthorized to access this resource'
            ]);
        }
    }

    private function validateRequest($request)
    {
        $validate = Validator::make($request->all(), [
            'name'          => 'required',
        ], [
            'name.required' => 'Nama customer wajib diisi',
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
