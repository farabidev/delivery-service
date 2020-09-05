<?php

namespace App\Application\Validator;

abstract class DeliveryValidator {
    public function validateDeliveryData($data, $requiredProperties = array()) {
        if (!$requiredProperties) {
            $requiredProperties = $this->requiredFields();
        }
        $isValid = true;
        $tmp = json_decode(json_encode($data), true);
        $numberFields = $this->numberFields();
        $missingProperty = '';
        foreach($requiredProperties as $propertyKey => $propertyValue){
            if (!is_array($propertyValue) && (!isset($tmp[$propertyValue]) || empty($tmp[$propertyValue])) || (in_array($propertyValue, $numberFields) && !is_numeric($tmp[$propertyValue]))){
                $missingProperty = $propertyValue;
                $isValid = false;
                break;
            }
            if (is_array($propertyValue)){
                foreach($propertyValue as $sub => $value){
                    if (!isset($tmp[$propertyKey][$value]) || empty($tmp[$propertyKey][$value]) || (in_array($value, $numberFields) && !is_numeric($tmp[$propertyKey][$value]))){
                        $missingProperty = $propertyKey . '->'. $value;
                        $isValid = false;
                        break;
                    }
                }
            }
        }

        return array('isValid' => $isValid, 'errorMessage' => $isValid ? '' : 'Missing / Invalid `'.$missingProperty.'` from JSON');
    }

    protected function numberFields() {
        return array('weight');
    }
    protected function requiredFields(){
        return array('customer' => array('name','address'),'deliveryType','source','weight');
    }

}