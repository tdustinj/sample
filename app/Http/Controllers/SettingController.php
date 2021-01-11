<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');

        //  $this->middleware('log')->only('index');

        //$this->middleware('subscribed')->except('store');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $setting = factory(\App\Models\Setting::class, 10)->make();
        return $setting;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return "Create";
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

            $setting = new Setting();

            $setting->group = $request->group ;
            $setting->option_name = $request->option_name ;
            $setting->option_type = $request->option_type ;
            $setting->option_value = $request->option_value ;
            $setting->status = $request->status ;


            $setting->save();
            return response()->json([
                'data' => $setting->toArray()
            ], 200);
        }
        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Unable to Save Setting", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $setting = Setting::findOrFail($id);
            return response()->json([
                'data' => $setting->toArray()
            ], 200);
        }

        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Setting Not Found for id: $id", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        return "Edit";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $setting = Setting::findOrFail($id);
            $setting->group = $request->group ;
            $setting->option_name = $request->option_name ;
            $setting->option_type = $request->option_type ;
            $setting->option_value = $request->option_value ;
            $setting->status = $request->status ;


            $setting->save();

        }
        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Unable to Update Setting", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        return "Destroy";

    }

    public function getOptionNameSet($optionName){
        try {
            $setting = Setting::where('option_name', '=', $optionName)->get();
            return response()->json([
                'data' => $setting->toArray()
            ], 200);
        }

        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Option Name Not Found for id: $optionName", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }
    public function search(Request $request)

    {

        $searchKey = $request->get('searchKey');
        $searchValue = $request->get('searchValue');
        try {
            $setting = Setting::where($searchKey, 'like', $searchValue .'%')->orderBy($searchKey, 'desc')
                ->take(10)
                ->get();
            return response()->json([
                'data' => $setting->toArray()
            ], 200);
        }

        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "No Settings Found Matching $searchValue", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }

    }
}
