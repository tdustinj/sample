<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Platform;

class PlatformController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');

        //$this->middleware('log')->only('index');

        //$this->middleware('subscribed')->except('store');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $platform = new Platform();
                $platform->platform_code = $request->platform_code;
                $platform->Platform_name = $request->state_code;
            $platformStateTax->save();

            return response()->json([
                'data' => $platformStateTax
            ], 200);
        }
        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Unable to Create Platform", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
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
            $platform = new Platform();
                $platform->platform_code = $request->platform_code;
                $platform->platform_name = $request->platform_name;
                $platform->active = 1;
            $platform->save();

            return response()->json([
                'data' => $platform
            ], 200);
        }
        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Unable to Create Platform", 'exceptionMessage' => $e->getMessage())
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
            $platforms = platform::all();
            return response()->json([
                'data' => $platforms->toArray()
            ], 200);
        }

        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Could not get Platforms info", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $data = $request->all();

            $platform = Platform::where('platform_code', $data['platform_code'])->first();
            // $platform->platform_name = $data['platform_name'];
            // $platform->platform_code = (isset($data['platform_code_new']) ? $data['platform_code_new'] : $data['platform_code']);
            $platform->active = $data['active'];
            $platform->save();

            return response()->json([
                'data' => $platform
            ], 200);
                
            }
        catch(exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Unable to Update platform", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($platformCode)
    {
        try {
            $platform = Platform::where('platform_code', $platformCode)->first();
            $platform->active = 0;
            $platform->save();
            return response()->json([
                'data' => $platform->toArray()
            ], 200);
        }

        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Platform could not be deleted: $platformCode", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }

    public function getAllPlatforms()
    {
        try {
            $platforms = Platform::all();

            if (empty($platforms)) {
                throw new Exception('Error retrieving Platforms.');
            }

            return response()->json([
                'data' => $platforms
            ], 200);
        }
        catch(Exception $e) {
            return response()->json([
                'data' => array('error'=> "Unable to load Platforms.", 'exceptionMessage' => $e->getMessage())
            ], 500);
        }
    }
}
