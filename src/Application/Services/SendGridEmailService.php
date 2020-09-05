<?php
declare(strict_types=1);

namespace App\Application\Services;

use App\Application\Interfaces\EmailServiceInterface;

class SendGridEmailService implements EmailServiceInterface
{
    private $settings;
    public function __construct($emailServiceSettings)
    {
        $this->settings = $emailServiceSettings;
    }
    public function connect($post)
    {
        $ch = curl_init($this->settings['url']);
        $username = (isset($this->settings['username']) && !empty($this->settings['username'])) ? $this->settings['username'] : '';
        $password = (isset($this->settings['password']) && !empty($this->settings['password'])) ? $this->settings['password'] : '';
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        $result = curl_exec($ch);
        curl_close($ch);

        // for test purposes
        $result = array('sent' => false);
        return json_decode(json_encode($result))->sent;
    }
}
