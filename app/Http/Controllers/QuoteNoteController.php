<?php

namespace App\Http\Controllers;

use App\Models\QuoteNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuoteNoteController extends Controller
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
            $data['fk_user_id'] = Auth::id();
            $newNote = QuoteNote::create($data);
            $newNote->user;
            $newNote->to;
            $newNote->note_type;

            return response()->json([
                'data' => $newNote
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'data' => array('error' => "No Order Found", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\QuoteNote  $QuoteNote
     * @return \Illuminate\Http\Response
     */
    public function show(QuoteNote $QuoteNote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\QuoteNote  $QuoteNote
     * @return \Illuminate\Http\Response
     */
    public function edit(QuoteNote $QuoteNote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\QuoteNote  $QuoteNote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, QuoteNote $QuoteNote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\QuoteNote  $QuoteNote
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuoteNote $QuoteNote)
    {
        //
    }
}
