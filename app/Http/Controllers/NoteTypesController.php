<?php

namespace App\Http\Controllers;

use App\Models\NoteTypes;
use Illuminate\Http\Request;

class NoteTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $noteType = new NoteTypes();
        $noteType->note_type = $data['note_type'];
        $noteType->note_type_code = $data['note_type'];
        $noteType->save();

        return response()->json([
            'data' => $noteType->toArray()
        ], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NoteTypes  $noteTypes
     * @return \Illuminate\Http\Response
     */
    public function show(NoteTypes $noteTypes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NoteTypes  $noteTypes
     * @return \Illuminate\Http\Response
     */
    public function edit(NoteTypes $noteTypes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NoteTypes  $noteTypes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $request->all();

        try {
            $noteType = NoteTypes::where('note_type_code', $data['note_type_code'])->first();
            $noteType->note_type = $data['note_type'];
           // $noteType->note_type_code = (isset($data['note_type_code_change']) ? $data['note_type_code_change'] :  $data['note_type_code'] );
            $noteType->save();
            return response()->json([
                'data' => $noteType->toArray()
            ], 200);
        }

        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "No NoteType Found Matching", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NoteTypes  $noteTypes
     * @return \Illuminate\Http\Response
     */
    public function destroy($noteTypeCode)
    {
        //

        try {
            $noteType = NoteTypes::where('note_type_code', $noteTypeCode)->first();
            $noteType->delete();
            return response()->json([
                'data' => "Succesfully Deleted NoteType"
            ], 200);
        }

        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Platform could not be deleted: $noteTypeCode", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }

    public function noteTypes()
    {
        try {
            $noteTypes = NoteTypes::all();
            return response()->json([
                'data' => $noteTypes->toArray()
            ], 200);
        }

        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "No Accounts Found Matching", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }

    }
}
