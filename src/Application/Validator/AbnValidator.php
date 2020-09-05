<?php

namespace App\Application\Validator;

class AbnValidator
{
    public function validate($abn)
    {
        $abn = str_replace(' ', '', $abn);

        // testing purposes
        // Valid ABN: 1234567890
        $successAbn = "1234567890";
        $abnValidationAPI = 'https://fakeAbnLookup/lookupAbn?Abn='.$abn;
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );
        /*  Uncomment below to validate ABN
            use file_get_contents method to get the result from the API

            $result = file_get_contents($abnValidationAPI, false, stream_context_create($arrContextOptions));
            $resultArr = json_decode($result, true);
            return $resultArr['Valid'] == "true";
        */

        return $abn == $successAbn;
    }
}
