<?php

use App\Controller\BotController;
use App\Controller\TeamspeakController;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface;

require_once __DIR__ . "/../config/includes.php";

// Instantiate App
$app = AppFactory::create();

$app->addRoutingMiddleware();

$customErrorHandler = function (
    ServerRequestInterface $request,
    Throwable $exception,
    bool $displayErrorDetails
) use ($app) {
    $payload = ['error' => $exception->getMessage()];
    $response = $app->getResponseFactory()->createResponse();
    return $response->withJson($payload)->withStatus(405);
};

// Add error middleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);

/** ROUTER BOT */
$app->get('/api/v1/bot[/{id}]', BotController::class . ':show');
$app->post('/api/v1/bot/create', BotController::class . ':create');
$app->put('/api/v1/bot/update/{id}', BotController::class . ':update');
$app->delete('/api/v1/bot/delete/{id}', BotController::class . ':delete');

/** ROUTER TEAMSPEAK */
$app->get('/api/v1/teamspeak[/{id}]', TeamspeakController::class . ':show');
$app->post('/api/v1/teamspeak/create', TeamspeakController::class . ':create');
$app->put('/api/v1/teamspeak/update/{id}', TeamspeakController::class . ':update');
$app->delete('/api/v1/teamspeak/delete/{id}', TeamspeakController::class . ':delete');

$app->run();
