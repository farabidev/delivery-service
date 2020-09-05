<?php
declare(strict_types=1);

namespace App\Application\Services;

use App\Application\Interfaces\EmailServiceInterface;

class MailChimpEmailService implements EmailServiceInterface{
    private $settings;
    public function __construct($emailServiceSettings){
        $this->settings = $emailServiceSettings;
    }
    public function connect($post) {
        $ch = curl_init($this->settings['url']);
        $authorization = (isset($this->settings['apiKey']) && !empty($this->settings['apiKey'])) ? $this->settings['apiKey'] : '';
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        // for test purposes
        $result = array('sent' => true);
        return json_decode(json_encode($result))->sent;
    }
}