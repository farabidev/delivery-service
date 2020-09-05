<?php
declare(strict_types=1);

use App\Application\Actions\Delivery\SendDeliveryAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Service is working!');
        return $response;
    });

    $app->group('/delivery', function (Group $group) {
        $group->post('/send', SendDeliveryAction::class);
    });
};