<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Processors\Delivery;

use App\Application\Actions\ActionPayload;
use Tests\TestCase;

class enterpriseDeliveryProcessorTest extends TestCase
{
    public function testSuccessEnterpriseDeliveryProcessor()
    {
        $app = $this->getAppInstance();

        $jsonData = [
            "customer" => array(
                "name" => "Farabi Djauhari",
                "address" => "56 Pitt Street, 2000, Sydney"
            ),
            "deliveryType" => "enterpriseDelivery",
            "source" => "direct",
            "onBehalf" => "Developers Community",
            "enterprise" => array(
                "name" => "Fake Company",
                "type" => "PtyLtd",
                "abn" => "1234567890"
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

    public function testInvalidAbn()
    {
        $app = $this->getAppInstance();

        $jsonData = [
            "customer" => array(
                "name" => "Farabi Djauhari",
                "address" => "56 Pitt Street, 2000, Sydney"
            ),
            "deliveryType" => "enterpriseDelivery",
            "source" => "direct",
            "onBehalf" => "Developers Community",
            "enterprise" => array(
                "name" => "Info Salons",
                "type" => "PtyLtd",
                "abn" => "FakeABN"
            ),
            "weight" => 1500
        ];

        $request = $this->createRequest('POST', '/delivery/send', ['HTTP_ACCEPT' => 'application/json'])->withParsedBody($jsonData);

        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        $jsonPayload = json_decode(json_encode(["status" => "Failed", "message" => "Invalid ABN"]));
        $expectedPayload = new ActionPayload(200, $jsonPayload);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    public function testEnterpriseNotFound()
    {
        $app = $this->getAppInstance();

        $jsonData = [
            "customer" => array(
                "name" => "Farabi Djauhari",
                "address" => "56 Pitt Street, 2000, Sydney"
            ),
            "deliveryType" => "enterpriseDelivery",
            "source" => "direct",
            "onBehalf" => "Developers Community",
            "weight" => 1500
        ];

        $request = $this->createRequest('POST', '/delivery/send', ['HTTP_ACCEPT' => 'application/json'])->withParsedBody($jsonData);

        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        $jsonPayload = json_decode(json_encode(["status" => "Failed", "message" => "Missing / Invalid `enterprise->name` from JSON"]));
        $expectedPayload = new ActionPayload(200, $jsonPayload);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}