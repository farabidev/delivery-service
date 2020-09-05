<?php
declare(strict_types=1);

namespace App\Application\Actions\Delivery;

use App\Application\Processors\Delivery\EnterpriseDeliveryProcessor;
use App\Application\Processors\Delivery\PersonalDeliveryExpressProcessor;
use App\Application\Processors\Delivery\PersonalDeliveryProcessor;
use Psr\Http\Message\ResponseInterface as Response;

class SendDeliveryAction extends DeliveryAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->prepareDelivery(json_decode(json_encode($this->request->getParsedBody())));
        return $this->respondWithData($data);
    }

    private function getDeliveryType($data) {
        if (property_exists($data, 'deliveryType') && !empty($data->deliveryType)){
            switch ($data->deliveryType) {
                case 'personalDelivery':
                    return new PersonalDeliveryProcessor($data);
                case 'personalDeliveryExpress':
                    return new PersonalDeliveryExpressProcessor($data, $this->container->get('settings')['emailService']);
                case 'enterpriseDelivery':
                    return new EnterpriseDeliveryProcessor($data);
                default:
                    return false;
            }
        }
    }

    private function prepareDelivery($data) {
        $delivery = $this->getDeliveryType($data);
        if (!$delivery){
            return array('status' => 'Failed', 'message' => 'Unknown Delivery Type');
        }
        return $delivery->send();
    }
}


