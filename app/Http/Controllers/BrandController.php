<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;



class BrandController extends Controller
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
    try {
      $categories = Brand::all()->take(5);
      return response()->json([
        'data' => $categories->toArray()
      ], 200);
    } catch (exception $e) {
      return response()->json([
        'data' => array('error' => "No Brands Found", 'exceptionMessage' => $e->getMessage())
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
    //
    try {
      $brand = new Brand;
      $brand->brand_name = $request->brand_name;
      $brand->parent_company = $request->brand_name;
      $brand->active = 1;
      $brand->brand_class = "product";

      $brand->save();

      return response()->json([
        'data' => $brand->toArray()
      ], 200);
    } catch (exception $e) {
      return response()->json([
        'data' => array('error' => "Unable to Store Brand", 'exceptionMessage' => $e->getMessage())
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
      $brand = Brand::findOrFail($id);
      return response()->json([
        'data' => $brand->toArray()
      ], 200);
    } catch (exception $e) {
      return response()->json([
        'data' => array('error' => "Brand not found for id: $id", 'exceptionMessage' => $e->getMessage())
      ], 404);
    }
  }



  public function search(Request $request)

  {
    $searchKey = $request->get('searchKey');
    $searchValue = $request->get('searchValue');
    try {
      $brand = Brand::where($searchKey, 'like', $searchValue . '%')->orderBy($searchKey, 'desc')
        ->take(10)
        ->get();
      return response()->json([
        'data' => $brand->toArray()
      ], 200);
    } catch (exception $e) {
      return response()->json([
        'data' => array('error' => "No Brands Found Matching $searchValue", 'exceptionMessage' => $e->getMessage())
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
    try {
      $brand = Brand::findOrFail($id);


      return response()->json([
        'data' => $brand->toArray()
      ], 200);
    } catch (exception $e) {
      return response()->json([
        'data' => array('error' => "Brand not found for id: $id", 'exceptionMessage' => $e->getMessage())
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
  public function update(Request $request, $id)
  {
    try {
      $brand = Brand::findOrFail($id);
      $brand->brand_name = $request->brand_name;

      $brand->save();
      return response()->json([
        'data' => $brand->toArray()
      ], 200);
    } catch (exception $e) {
      return response()->json([
        'data' => array('error' => "Brand not found for id: $id", 'exceptionMessage' => $e->getMessage())
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
    try {
      $brand = Brand::findOrFail($id);
      $brand->delete();


      return response()->json([
        'data' => $brand->toArray()
      ], 200);
    } catch (exception $e) {
      return response()->json([
        'data' => array('error' => "Brand not found for id: $id", 'exceptionMessage' => $e->getMessage())
      ], 404);
    }
  }


  public function allBrands()
  {
    try {
      $brands = Brand::all()->pluck('id', 'brand_name');
      return response()->json([
        'data' => $brands->toArray()
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'data' => array('error' => "No Categories Found", 'exceptionMessage' => $e->getMessage())
      ], 404);
    }
  }
}

