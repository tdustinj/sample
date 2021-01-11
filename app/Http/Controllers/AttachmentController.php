<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    //
    public function index(){
        return view('attachmentUploadTest');
    }
    public function getAttachmentsList($workOrderId){
        /*
         * Accepts either Invoice or Order Id and returns a list of attachments
         */
        try {
            $listOfFiles = Storage::disk('s3')->files($workOrderId . '/');
            return response()->json([
                'data' => array('files'=> $listOfFiles->toArray())
            ], 200);

        }
        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "No Attachments found for $workOrderId", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
    }

    public function attach(Request $request){
        /*
         * Adds a attachment
         *
         * expects attachment fields + fk_workorder_id
         *
         */
        try {
            $newAttachment = new Attachment();
            $newAttachment->fk_workorder_id = $request->workOrderId;
            $newAttachment->fk_user_id = $request->userId;
            $newAttachment->file_purpose = $request->filePurpose;

            $newAttachment->file_path = $request->workOrderId;
            $newAttachment->file_description = $request->fileDescription;
            $newAttachment->save();

        }
        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Attachment Cound Not Be Created", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }
        try {
            // filename must be attachment
            $file = $request->file('attachment');

            $newAttachment->file_name = time().$file->getClientOriginalName();

            Storage::disk('s3')->makeDirectory($newAttachment->fk_workOrderId);

            $filePath =  $newAttachment->fk_workorder_id . '/' . $newAttachment->file_name ;
           // echo $filePath;
            Storage::disk('s3')->put($filePath, file_get_contents($file->getRealPath() ));
            $newAttachment->save();
            return back()->with('success','Attchment Uploaded successfully');
        }
        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Attachment Could Not Be Saved: values", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }

    }

    public function delete($id){

        /*
         *  Deletes an attachment and removes it form file storage
         *
         */
        try {
            // filename must be attachment

            $attachmentToDelete = Attachment::findOrFail($id);

            $filePath =  $attachmentToDelete->fk_workorder_id . '/' ;
            Storage::disk('s3')->delete( $filePath . $attachmentToDelete->file_name);
            return back()->with('success','Attachment Deleted successfully');
        }
        catch(\Exception $e)
        {
            return response()->json([
                'data' => array('error'=> "Attachment Could Not Be Saved", 'exceptionMessage' => $e->getMessage())
            ], 404);
        }

    }
}
