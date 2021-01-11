<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;



class CategoryController extends Controller
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
      $categories = Category::all()->take(5);
      return response()->json([
        'data' => $categories->toArray()
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'data' => array('error' => "No Categories Found", 'exceptionMessage' => $e->getMessage())
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
      $category = new Category;
      $category->category_name = $request->category_name;
      $category->active = 1;
      $category->category_class = "product";

      $category->save();

      return response()->json([
        'data' => $category->toArray()
      ], 200);
    } catch (exception $e) {
      return response()->json([
        'data' => array('error' => "Unable to Store Category", 'exceptionMessage' => $e->getMessage())
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
      $category = Category::findOrFail($id);
      return response()->json([
        'data' => $category->toArray()
      ], 200);
    } catch (exception $e) {
      return response()->json([
        'data' => array('error' => "Category not found for id: $id", 'exceptionMessage' => $e->getMessage())
      ], 404);
    }
  }



  public function search(Request $request)

  {
    $searchKey = $request->get('searchKey');
    $searchValue = $request->get('searchValue');
    try {
      $category = Category::where($searchKey, 'like', $searchValue . '%')->orderBy($searchKey, 'desc')
        ->take(10)
        ->get();
      return response()->json([
        'data' => $category->toArray()
      ], 200);
    } catch (exception $e) {
      return response()->json([
        'data' => array('error' => "No Categorys Found Matching $searchValue", 'exceptionMessage' => $e->getMessage())
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
      $category = Category::findOrFail($id);


      return response()->json([
        'data' => $category->toArray()
      ], 200);
    } catch (exception $e) {
      return response()->json([
        'data' => array('error' => "Category not found for id: $id", 'exceptionMessage' => $e->getMessage())
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
      $category = Category::findOrFail($id);
      $category->category_name = $request->category_name;

      $category->save();
      return response()->json([
        'data' => $category->toArray()
      ], 200);
    } catch (exception $e) {
      return response()->json([
        'data' => array('error' => "Category not found for id: $id", 'exceptionMessage' => $e->getMessage())
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
      $category = Category::findOrFail($id);
      $category->delete();


      return response()->json([
        'data' => $category->toArray()
      ], 200);
    } catch (exception $e) {
      return response()->json([
        'data' => array('error' => "Category not found for id: $id", 'exceptionMessage' => $e->getMessage())
      ], 404);
    }
  }

  public function allCategories()
  {
    try {
      $categories = Category::all()->pluck('id', 'category_name');
      return response()->json([
        'data' => $categories->toArray()
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'data' => array('error' => "No Categories Found", 'exceptionMessage' => $e->getMessage())
      ], 404);
    }
  }

  public function allCategoriesFull()
  {
    try {
      $categories = Category::all();
      return response()->json([
        'data' => $categories->toArray()
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'data' => array('error' => "No Categories Found", 'exceptionMessage' => $e->getMessage())
      ], 404);
    }
  }
}
