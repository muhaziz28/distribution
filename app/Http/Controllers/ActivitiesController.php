<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivitiesRequest;
use App\Http\Requests\UpdateActivitiesRequest;
use App\Models\Activities;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class ActivitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('');
    }

    public function data(Request $request)
    {

        $activities = Activities::all();
        return DataTables::of($activities)->addIndexColumn()->toJson();

        return response()->json([
            'success' => false,
            'message' => 'You are unauthorized to access this resource'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActivitiesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Activities $activities)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activities $activities)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActivitiesRequest $request, Activities $activities)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activities $activities)
    {
        //
    }
}
