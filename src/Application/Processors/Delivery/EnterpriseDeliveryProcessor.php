<?php
declare(strict_types=1);

namespace App\Application\Processors\Delivery;
use App\Application\Interfaces\DeliveryInterface;
use App\Application\Validator\AbnValidator;
use App\Application\Validator\DeliveryValidator;

class EnterpriseDeliveryProcessor extends DeliveryValidator implements DeliveryInterface {
    private $data;
    private $abnValidator;
    function __construct($data){
        $this->data = $data;
        $this->abnValidator = new AbnValidator();
    }
    public function send() {
        $validateDeliveryData = $this->validateDeliveryData($this->data, $this->getRequiredProperties());
        if (!$validateDeliveryData['isValid']){
            return array('status' => 'Failed', 'message' => $validateDeliveryData['errorMessage']);
        }
        if (property_exists($this->data, 'enterprise') && property_exists($this->data->enterprise, 'abn')){
            $validateAbn = $this->validateAbn($this->data->enterprise->abn);
            return $validateAbn ? array('status' => 'Success', 'message' => 'Delivery send successfully') : array('status' => 'Failed', 'message' => 'Invalid ABN');
        }else{
            return array('status' => 'Failed', 'message' => 'Failed to process enterprise delivery');
        }
    }

    private function getRequiredProperties(){
        return array_merge($this->requiredFields(), array('onBehalf','enterprise' => array('name','type','abn')));
    }

    protected function validateAbn($abn) {
        return $this->abnValidator->validate($abn);
    }
}