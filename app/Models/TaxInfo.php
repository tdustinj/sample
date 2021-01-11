<?php
/**
 * Created by PhpStorm.
 * User: rob
 * Date: 5/10/17
 * Time: 9:45 AM
 */

namespace App\Models;


class TaxInfo
{


   var $states;

    public function __construct()
    {
        $states = array(
            'AL'=>'ALABAMA',
            'AK'=>'ALASKA',
            'AS'=>'AMERICAN SAMOA',
            'AZ'=>'ARIZONA',
            'AR'=>'ARKANSAS',
            'CA'=>'CALIFORNIA',
            'CO'=>'COLORADO',
            'CT'=>'CONNECTICUT',
            'DE'=>'DELAWARE',
            'DC'=>'DISTRICT OF COLUMBIA',
            'FM'=>'FEDERATED STATES OF MICRONESIA',
            'FL'=>'FLORIDA',
            'GA'=>'GEORGIA',
            'GU'=>'GUAM GU',
            'HI'=>'HAWAII',
            'ID'=>'IDAHO',
            'IL'=>'ILLINOIS',
            'IN'=>'INDIANA',
            'IA'=>'IOWA',
            'KS'=>'KANSAS',
            'KY'=>'KENTUCKY',
            'LA'=>'LOUISIANA',
            'ME'=>'MAINE',
            'MH'=>'MARSHALL ISLANDS',
            'MD'=>'MARYLAND',
            'MA'=>'MASSACHUSETTS',
            'MI'=>'MICHIGAN',
            'MN'=>'MINNESOTA',
            'MS'=>'MISSISSIPPI',
            'MO'=>'MISSOURI',
            'MT'=>'MONTANA',
            'NE'=>'NEBRASKA',
            'NV'=>'NEVADA',
            'NH'=>'NEW HAMPSHIRE',
            'NJ'=>'NEW JERSEY',
            'NM'=>'NEW MEXICO',
            'NY'=>'NEW YORK',
            'NC'=>'NORTH CAROLINA',
            'ND'=>'NORTH DAKOTA',
            'MP'=>'NORTHERN MARIANA ISLANDS',
            'OH'=>'OHIO',
            'OK'=>'OKLAHOMA',
            'OR'=>'OREGON',
            'PW'=>'PALAU',
            'PA'=>'PENNSYLVANIA',
            'PR'=>'PUERTO RICO',
            'RI'=>'RHODE ISLAND',
            'SC'=>'SOUTH CAROLINA',
            'SD'=>'SOUTH DAKOTA',
            'TN'=>'TENNESSEE',
            'TX'=>'TEXAS',
            'UT'=>'UTAH',
            'VT'=>'VERMONT',
            'VI'=>'VIRGIN ISLANDS',
            'VA'=>'VIRGINIA',
            'WA'=>'WASHINGTON',
            'WV'=>'WEST VIRGINIA',
            'WI'=>'WISCONSIN',
            'WY'=>'WYOMING',
            'AE'=>'ARMED FORCES AFRICA \ CANADA \ EUROPE \ MIDDLE EAST',
            'AA'=>'ARMED FORCES AMERICA (EXCEPT CANADA)',
            'AP'=>'ARMED FORCES PACIFIC'
        );



    }

    /*
      * This method generate signature has a dependency on a java jar file pri=ovide from walmart DigitalSignatureUtil
      * it assumes that the apiUrlIs Set and and api<ethod is set.
      * It also requires Private key provided by walmart and consumer id provided by walmart
      *
      */

    public function convertStateToAbrev($stateString){
        // todo: need to add a webservice do this
        foreach($this->states as $key => $value){
            $stateString = strtoupper($stateString);
            if($stateString == $value){
                return $key;
            }
        }
        return $stateString;
    }


    public function getTaxZoneNexus($state){
        $state = strtoupper($state);
        if(!array_key_exists($state, $this->states)){
           $state = $this->convertStateToAbrev($state);
        }
        $taxInfo = Setting::where('key_type', '=', 'TAX_ZONE')->where('key', '=',$state)->get();
        if(sizeof($taxInfo)) {
            return $taxInfo[0]->value;
        }
        else{
            // we have no matching tax_zone so we assume it is not in our nexus
            return false;
        }

    }

    public function getTaxByZone($taxZone = 'AZ', $zoneClass = 'GEN_PRODUCT', $amount){
        $taxRateInfo = Setting::where('key_type', '=', 'TAX_ZONE_DETAIL')->where('key', '=',$taxZone)->where('value','=', $zoneClass)->get();
        if(sizeof($taxRateInfo)) {
            $taxRate = $taxRateInfo[0]->contents;
        }
        else{
            $taxRate = 0;
        }
        return $taxRate * $amount;
    }



}