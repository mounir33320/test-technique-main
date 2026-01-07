<?php

use Aucoffre\Infrastructure\Middleware\ApiKeyAuthMiddleware;
use Aucoffre\Infrastructure\Middleware\ErrorHandlerMiddleware;
use Aucoffre\Infrastructure\Middleware\PDOTransactionMiddleware;
use Aucoffre\Presentation\Http\Actions\ListAccountAction;
use Aucoffre\Presentation\Http\Actions\MakeATransferAction;
use Aucoffre\Presentation\Http\Actions\SayHelloAction;
use Slim\App;


/**
 * Enregistrement des routes de l'api
 * @param App $app
 * @return void
 */
return function (App $app) {
    $apiKey = $app->getContainer()->get('api_key');
    $pdo = $app->getContainer()->get(PDO::class);

    $app->get('/', SayHelloAction::class)->setName('sayHello');
    $app->group('/api', function () use ($app) {
        $app->get('/accounts', ListAccountAction::class)->setName('listAccount');
        $app->post('/accounts/{id}/transfer', MakeATransferAction::class)->setName('transfer');
    })
        ->addMiddleware(new PDOTransactionMiddleware($pdo))
        ->addMiddleware(new ApiKeyAuthMiddleware($apiKey))
        ->addMiddleware(new ErrorHandlerMiddleware())
    ;
};