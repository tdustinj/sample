<?php

namespace App\Utilities;

use DB;

class CustomHelpers {

	public function timeStamp()
	{
	    date_default_timezone_set('America/Phoenix');
	    return date("Y-m-d h:m:s");
	}



	public function insertInvoiceMessage($invoice, $msg, $user) {

		$right_now = self::timeStamp();

	    $messagequery = "INSERT INTO messages (msg_time, invoice, msg_type, msg_from, msg_to, msg) 
	                      VALUES ('$right_now', '$invoice', 'shipping', 'AutoLoaded', 'system', '$msg')";
	    DB::connection('oldpos')->insert($messagequery); 

	}

}



?>