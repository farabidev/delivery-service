<?php
declare(strict_types=1);

namespace App\Application\Processors\Delivery;

use App\Application\Interfaces\DeliveryInterface;
use App\Application\Validator\DeliveryValidator;

class PersonalDeliveryProcessor extends DeliveryValidator implements DeliveryInterface {
    private $data;
    function __construct($data){
        $this->data = $data;
    }
    public function send() {
        $validateDeliveryData = $this->validateDeliveryData($this->data, array());
        if (!$validateDeliveryData['isValid']){
            return array('status' => 'Failed', 'message' => $validateDeliveryData['errorMessage']);
        }

        return array('status' => 'Success', 'message' => 'Delivery send successfully');
    }
}