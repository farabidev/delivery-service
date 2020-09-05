<?php
declare(strict_types=1);

namespace App\Application\Services;

use App\Application\Interfaces\EmailServiceInterface;

class EmailService {
    private $emailService;
    private $campaign;
    public function __construct(EmailServiceInterface $emailService, $campaign) {
        $this->emailService = $emailService;
        $this->campaign = $campaign;
    }

    public function send(){
        return $this->emailService->connect($this->campaign);
    }
}