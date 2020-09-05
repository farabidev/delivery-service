<?php
declare(strict_types=1);

namespace App\Application\Processors\Delivery;

use App\Application\Interfaces\DeliveryInterface;
use App\Application\Services\EmailService;
use App\Application\Services\MailChimpEmailService;
use App\Application\Services\SendGridEmailService;
use App\Application\Validator\DeliveryValidator;

class PersonalDeliveryExpressProcessor extends DeliveryValidator implements DeliveryInterface
{
    private $data;
    private $settings;
    private $defaultEmailService;
    public function __construct($data, $settings)
    {
        $this->data = $data;
        $this->settings = $settings;
        $this->defaultEmailService = "mailChimp";
    }
    public function send()
    {
        $validateDeliveryData = $this->validateDeliveryData($this->data, $this->getRequiredProperties());
        if (!$validateDeliveryData['isValid']) {
            return array('status' => 'Failed', 'message' => $validateDeliveryData['errorMessage']);
        }
        // For Demo Purposes:
        // MailChimp will return success
        // Sendgrid / other email services will return unsuccessful
        $emailService = $this->defaultEmailService;
        if (property_exists($this->data->campaign, 'service')) {
            $emailService = $this->data->campaign->service;
        }
        $sendEmailCampaign = $this->sendEmailCampaign($this->data->campaign, $emailService);
        return $sendEmailCampaign ? array('status' => 'Success', 'message' => 'Delivery send successfully') : array('status' => 'Failed', 'message' => 'Failed to send email delivery');
    }

    private function getRequiredProperties()
    {
        return array_merge($this->requiredFields(), array('campaign'=> array('name','type','ad')));
    }

    protected function sendEmailCampaign($campaign, $emailService)
    {
        $getEmailService = $this->getEmailService($emailService);
        if ($getEmailService) {
            $emailService = new EmailService($this->getEmailService($emailService), $campaign);
            return $emailService->send();
        }
        return false;
    }

    protected function getEmailService($emailService)
    {
        switch ($emailService) {
            case 'mailChimp':
                return new MailChimpEmailService($this->settings[$emailService]);
            case 'sendGrid':
                return new SendGridEmailService($this->settings[$emailService]);
            default:
                return false;
        }
    }
}
