<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Delivery;

use App\Application\Actions\ActionPayload;
use Tests\TestCase;

class SendDeliveryTest extends TestCase
{
    public function testSuccessSendPersonalDelivery()
    {
        $app = $this->getAppInstance();

        $jsonData = [
            "customer" => array(
                "name" => "Johnny Bravo",
                "address" => "56 Pitt Street, 2000, Sydney"
            ),
            "deliveryType" => "personalDelivery",
            "source" => "web",
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

    public function testFailedWhenUnknownDeliveryType()
    {
        $app = $this->getAppInstance();

        $jsonData = [
            "customer" => array(
                "name" => "Johnny Bravo",
                "address" => "56 Pitt Street, 2000, Sydney"
            ),
            "deliveryType" => "unknownDelivery",
            "source" => "web",
            "weight" => 1500
        ];

        $request = $this->createRequest('POST', '/delivery/send', ['HTTP_ACCEPT' => 'application/json'])->withParsedBody($jsonData);

        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        $jsonPayload = json_decode(json_encode(["status" => "Failed", "message" => "Unknown Delivery Type"]));
        $expectedPayload = new ActionPayload(200, $jsonPayload);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    public function testFailedWhenWeightIsNotNumeric()
    {
        $app = $this->getAppInstance();

        $jsonData = [
            "customer" => array(
                "name" => "Johnny Bravo",
                "address" => "56 Pitt Street, 2000, Sydney"
            ),
            "deliveryType" => "personalDelivery",
            "source" => "web",
            "weight" => "notNumeric"
        ];

        $request = $this->createRequest('POST', '/delivery/send', ['HTTP_ACCEPT' => 'application/json'])->withParsedBody($jsonData);

        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        $jsonPayload = json_decode(json_encode(["status" => "Failed", "message" => 'Missing / Invalid `weight` from JSON']));
        $expectedPayload = new ActionPayload(200, $jsonPayload);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
        $this->assertIsNotInt($jsonData['weight']);
    }
}
