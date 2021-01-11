<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request, App\Models\Product, App\Models\Brand, App\Models\Category;

class ProductController extends Controller
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
  public function index(Request $request)
  {

    return $this->listProducts($request);
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
    //
    try {
      $product = Product::create($request->all());
      return response()->json([
        "data" => $product
      ]);
    } catch (Exception $e) {
      return response()->json([
        "error" => $e
      ]);
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
      $item = Product::findOrFail($id);
      return response()->json([
        'data' => $item->toArray()
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'data' => array('error' => "Product not found for id: $id", 'exceptionMessage' => $e->getMessage())
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
    //
    $product = Product::findOrFail($id);
    $product->update($request->all());
    return response()->json([
      "data" => $product
    ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $product = Product::findOrFail($id);
    $product->delete();
    return response()->json(["data" => "Record $id sucessfully deleted"]);
  }

  /*
   * List of Products based on filters defined by user
   * @param Request $request
   * @return \Illuminate\Http\Response
   */
  public function listProducts(Request $request)
  {
    // First, validate that the input queries are allowed
    // Nullable so that we can have an empty search later
    $validated = $request->validate([
      'category' => 'nullable|integer|exists:category,id',
      'brand' => 'nullable|integer|exists:brand,id',
      'description' => 'nullable|string',
      'modelTerm' => 'nullable|string',
      'upc' => 'nullable|string', // Needs to be set to string or request don't work
    ]);

    // If any of the criteria don't exist set them to null
    $category = $validated['category'] ?? '';
    $brand = $validated['brand'] ?? '';
    $modelNumber = $validated['modelTerm'] ?? '';
    $description = $validated['description'] ?? '';
    $upc = $validated['upc'] ?? '';
    $limit = 1000; // Reasonable chunk of data

    $products = (new Product)->newQuery();

    if ($brand) {
      $products->where('fk_brand_id', $brand);
    }

    if ($upc) {
      $products->where('upc', 'like', $upc . '%');
    }

    if ($category) {
      $products->where('fk_category_id', $category);
    }

    if ($description) {
      $products->where('description', 'like', $description . '%');
    }

    if ($modelNumber) {
      $products->where('model_number', 'like', $modelNumber . '%');
    }

    // Query the Product model with the IDs OR empty strings
    return response()->json(["data" => $products->limit($limit)->get()->values()]);
  }

  public function search(Request $request)

  {
    $searchKey = $request->get('searchKey');
    $searchValue = $request->get('searchValue');
    $searchSize = $request->get('searchSize');
    //print_r($searchKey);

    switch ($searchKey) {
      case 'brand':
        $brand = Brand::where('brand_name', '=', $searchValue)->get();
        $searchValue = $brand[0]['id'];
        //print_r("hit brand");
        break;
      case 'category':
        $category = Category::where('category_name', '=', $searchValue)->get();
        $searchValue = $category[0]['id'];
        //print_r("hit category");
        break;
      default:
        //print_r("hit default");
    }

    // if($searchKey === 'brand'){
    //     $brand = Brand::where('brand_name','=', $searchValue )->get();
    //     $searchValue = $brand[0]['id'];
    //     //print_r($searchValue);
    // }

    // if($searchKey === 'category'){
    //     $category = Category::where('category_name', '=', $searchValue)->get();
    //     echo "here";
    //     print_r($category);
    //     $searchValue = $category[0]['id'];
    // }



    try {
      $searchValue = $searchValue . "%";
      $products = Product::where($searchKey, 'like', $searchValue)->orderBy($searchKey, 'asc')->with(['category', 'brand'])
        ->take(50)
        ->get();

      if ($searchSize === 'light') {
        $modelList = array();
        foreach ($products as $product) {
          $modelList[] = array('label' => $product->model_number);
        }

        return response()->json([
          'data' => $modelList
        ], 200);
      } else {
        return response()->json([
          'data' => $products->toArray()
        ], 200);
      }

      // return response()->json([
      //     'data' => $products->toArray()
      // ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'data' => array('error' => "No Products Found Matching $searchValue", 'exceptionMessage' => $e->getMessage())
      ], 404);
    }
  }

  public function getLegacyProductIds(Request $request)
  {
    /*
     * THis method will be depracated once this roles into production
     * it is used for Listing Manager to aquire the product.ids for inventory calls
     * to the old pos "walts2.Inventory
     */

    $osposProductIds = json_decode($request->input('productIds'));
    return response()->json([
      'data' => $osposProductIds->toArray()
    ], 200);
  }
}
