<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request, App\Models\Brand, App\Models\Category;
use App\Mail\VendorPurchaseOrder;
use App\Traits\PDFActions;
use App\Repositories\Purchase\PurchaseRepositoryContract;
use Exception;

class PurchaseController extends Controller
{
    use PDFActions;

    public function __construct(PurchaseRepositoryContract $purchaseRepository)
    {
        $this->middleware('auth:api');
        $this->purchaseRepository = $purchaseRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $recentPOs = $this->purchaseRepository->getRecent();
        //print_r($recentPOs . "hey0");

        return response()->json([
            'data' => $recentPOs
        ], 200);
        //return 'index';
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
            $purchaseOrder = $this->purchaseRepository->createFromRequest($request);
            return response()->json([
                'data' => $purchaseOrder
            ], 200);
        } catch (exception $e) {
            return response()->json([
                'data' => array('error' => "Unable to Store Inventory", 'exceptionMessage' => $e->getMessage())
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
            $po = $this->purchaseRepository->getPurchaseById($id);
            return response()->json([
                'data' => $po
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'data' => array('error' => "Purchase Order not found for id: $id", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getFull($id)
    {
        try {
            $combinedData = $this->purchaseRepository->getFull($id);
            return response()->json([
                'data' => $combinedData
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'data' => array('error' => "Purchase Order not found for id: $id", 'exceptionMessage' => $e->getMessage())
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
            $purchaseOrder = $this->purchaseRepository->updateFromRequest($request, $id);
            return response()->json([
                'data' => $purchaseOrder
            ], 200);
        } catch (exception $e) {
            return response()->json([
                'data' => array('error' => "Unable to Update Purchase Order", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }

    public function submit($id)
    {
        /*
         * Need to add code to email vendor_id order desk or
         *  vendor API transaction request for back end command to submit PO
         */
        try {
            $purchaseOrder = $this->purchaseRepository->submit($id);
            return response()->json([
                'data' => $purchaseOrder->toArray()
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'data' => array('error' => "Unable to Update Purchase Order", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }

    public function receive(Request $request, $id)
    {
        print_r("receive");
        try {
            $purchaseOrder = $this->purchaseRepository->receive($request, $id);
            return response()->json([
                'data' => $purchaseOrder
            ], 200);
        } catch (exception $e) {
            return response()->json([
                'data' => array('error' => "Unable to Update Purchase Order", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }
    public function finish($id)
    {
        try {
            $purchaseOrder =  $this->purchaseRepository->finish($id);
            return response()->json([
                'data' =>  $purchaseOrder
            ], 200);
        } catch (exception  $e) {
            return response()->json([
                'data' => array('error' => "Unable to Update Purchase Order", 'exceptionMessage' =>  $e->getMessage())
            ], 404);
        }
    }

    public function search(Request  $request)
    {
        $searchKey =  $request->get('searchKey');
        $searchValue =  $request->get('searchValue');
        //print_r( $searchKey);

        switch ($searchKey) {
            case 'brand':
                $brand = Brand::where('brand_name', '=',  $searchValue)->get();
                $searchValue =  $brand[0]['id'];
                break;
            case 'category':
                $category = Category::where('category_name', '=',  $searchValue)->get();
                $searchValue =  $category[0]['id'];
                break;
            default:
        }

        try {
            $product = Purchase::where($searchKey, 'like',  $searchValue . '%')->orderBy($searchKey, 'desc')
                ->take(30)
                ->get();
            return response()->json([
                'data' =>  $product->toArray()
            ], 200);
        } catch (Exception  $e) {
            return response()->json([
                'data' => array('error' => "No Purchaser Orders Found Matching  $searchValue", 'exceptionMessage' =>  $e->getMessage())
            ], 404);
        }
    }

    private function pdfData($id)
    {
        $purchaseOrder =  $this->purchaseRepository->getPurchaseById($id);
        $vendor =  $purchaseOrder->vendor;
        $lineItems =  $purchaseOrder->items;
        $data = ['purchaseOrder' =>  $purchaseOrder, 'vendor' =>  $vendor, 'lineItems' =>  $lineItems];
        return  $data;
    }

    public function pdf($id,  $data = null)
    {
        $view = 'pdf.purchaseorder';
        if ($data === null) {
            $data =  $this->pdfData($id);
        }
        $pdf =  $this->createPDF($view,  $data);
        return  $pdf->download($data['purchaseOrder']->id . '.pdf');
    }

    public function preview($id, Request  $request)
    {
        $data =  $this->pdfData($id);
        $pdf =  $this->pdf($id,  $data);
        $mail = new VendorPurchaseOrder($pdf,  $data,  $request->message);
        return response()->json(['mail' =>   $mail->render()]);
    }

    public function email(Request   $request,   $id)
    {
        $data =   $this->pdfData($id);
        $pdf =   $this->pdf($id,   $data);
        $mail = new VendorPurchaseOrder($pdf,   $data,   $request->message);
        $this->sendPDF($mail,   $request->email);
    }
}
