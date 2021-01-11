<?php

namespace App\Models;

class ResizedSearchResults {

	public static function resultsToDataStructure($data, $fields) {

		$dataStructure = array();
       // print_r($fields);
        foreach($data as $fieldName => $value){
            //echo $fieldName . " " . $value . "\n\r";
            if(in_array($fieldName, $fields)) {
                $dataStructure[$fieldName] = $value;
            }
			}


		 //echo "<pre>";
		//print_r($dataStructure);
		return $dataStructure;
	}

}