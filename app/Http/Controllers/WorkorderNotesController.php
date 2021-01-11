<?php

namespace App\Http\Controllers;

use App\Models\WorkorderNotes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\WorkOrderNoteAdded;
use Exception;

class WorkorderNotesController extends Controller
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
        try {
            $data = $request->all();
            $data['fk_user_id_recipient'] = $data['fk_user_id_recipient'] === 0 ? null : $data['fk_user_id_recipient'];
            $data['fk_user_id'] = Auth::id();
            $newNote = WorkorderNotes::create($data);
            $newNote->load(['to', 'workOrder', 'user', 'note_type']);
            event(new WorkOrderNoteAdded($newNote));

            return response()->json([
                'data' => $newNote
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'data' => array('error' => "No Order Found", 'exceptionMessage' => $e->getMessage())
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkorderNotes  $workorderNotes
     * @return \Illuminate\Http\Response
     */
    public function show(WorkorderNotes $workorderNotes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WorkorderNotes  $workorderNotes
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkorderNotes $workorderNotes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WorkorderNotes  $workorderNotes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WorkorderNotes $workorderNotes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkorderNotes  $workorderNotes
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkorderNotes $workorderNotes)
    {
        //
    }
}
