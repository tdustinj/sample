<?php

namespace App\Traits;

use App;
use Mail;

trait PDFActions
{
	function createPDF($view, $data)
	{
		$pdf = App::make('snappy.pdf.wrapper');
		$pdf->loadView($view, $data);
		return $pdf;
	}

	function sendPDF($EmailClass, $to = 'thenerdscribe@Ryans-Mac-mini.local')
	{
		Mail::to($to)->send($EmailClass);
	}
}
