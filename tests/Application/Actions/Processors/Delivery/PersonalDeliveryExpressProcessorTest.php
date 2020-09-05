<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Processors\Delivery;

use App\Application\Actions\ActionPayload;
use Tests\TestCase;

class personalDeliveryExpressProcessorTest extends TestCase
{
    public function testSuccessPersonalDeliveryExpressProcessor()
    {
        $app = $this->getAppInstance();

        $jsonData = [
            "customer" => array(
                "name" => "Farabi Djauhari",
                "address" => "56 Pitt Street, 2000, Sydney"
            ),
            "deliveryType" => "personalDeliveryExpress",
            "source" => "email",
            "campaign" => array(
                "name" => "Christmas2018",
                "type" => "holiday",
                "abn" => "opportunity"
            ),
            "weight" => 1500
        ];

        $request = $this->createRequest('POST', '/delivery/send', ['HTTP_ACCEPT' => 'application/json'])->withParsedBody($jsonData);

        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        $jsonPayload = json_decode(json_encode(["status" => "Success", "message" => "Delivery send successfully"]));
        $expectedPayload = new ActionPayload(200, $jsonPayload);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    public function testFailedToSendEmailDelivery()
    {
        $app = $this->getAppInstance();

        $jsonData = [
            "customer" => array(
                "name" => "Farabi Djauhari",
                "address" => "56 Pitt Street, 2000, Sydney"
            ),
            "deliveryType" => "personalDeliveryExpress",
            "source" => "email",
            "campaign" => array(
                "service" => "unknownService",
                "name" => "Christmas2018",
                "type" => "holiday",
                "abn" => "opportunity"
            ),
            "weight" => 1500
        ];

        $request = $this->createRequest('POST', '/delivery/send', ['HTTP_ACCEPT' => 'application/json'])->withParsedBody($jsonData);

        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        $jsonPayload = json_decode(json_encode(["status" => "Failed", "message" => "Failed to send email delivery"]));
        $expectedPayload = new ActionPayload(200, $jsonPayload);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
