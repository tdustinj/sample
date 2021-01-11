<?php

namespace App\Http\Controllers;

use App\Models\InventoryConditions;
use Illuminate\Http\Request;

class InventoryConditionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $conditions = InventoryConditions::all();
        return response()->json([
            'data' => $conditions->toArray()
        ], 200);
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
        $inventoryCondition = new InventoryConditions();
        $inventoryCondition->timestamps = false;
        $inventoryCondition->condition = $data['condition'];
        $inventoryCondition->save();

        return response()->json([
            'data' => $inventoryCondition->toArray()
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InventoryConditions  $inventoryConditions
     * @return \Illuminate\Http\Response
     */
    public function show(InventoryConditions $inventoryConditions)
    {
        $conditions = InventoryConditions::all();
        return response()->json([
            'data' => $conditions->toArray()
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InventoryConditions  $inventoryConditions
     * @return \Illuminate\Http\Response
     */
    public function edit(InventoryConditions $inventoryConditions)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $request->all();

        $inventoryCondition = InventoryConditions::where('id', $data['id'])->first();
        $inventoryCondition->timestamps = false;
        $inventoryCondition->condition = $data['condition'];
        $inventoryCondition->save();

        return response()->json([
            'data' => $data
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InventoryConditions  $inventoryConditions
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $inventoryCondition = InventoryConditions::where('id', $id)->first();
        $inventoryCondition->delete();
        return response()->json([
            'data' => "Succesfully Deleted record"
        ], 200);
    }
}

// GET	        /nerds	          index	   nerds.index
// GET	        /nerds/create	  create   nerds.create
// POST         /nerds	          store	   nerds.store
// GET	        /nerds/{id}	      show	   nerds.show
// GET	        /nerds/{id}/edit  edit	   nerds.edit
// PUT/PATCH	/nerds/{id}	      update   nerds.update
// DELETE	    /nerds/{id}	      destroy  nerds.destroy