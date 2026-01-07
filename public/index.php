<?php

declare(strict_types=1);

use Aucoffre\Infrastructure\Middleware\ApiKeyAuthMiddleware;
use Slim\Factory\AppFactory;

//Récupération du container construit dans bootstrap.php
$container = require dirname(__DIR__) . '/bootstrap.php';

//Création application
AppFactory::setContainer($container);
$app = AppFactory::create();

//Enregistrement des routes
$routes = require ROOT_PATH . '/src/Presentation/routes.php';
$routes($app);

//Enregistrement middlewares natif
$app->addRoutingMiddleware();

$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(
    displayErrorDetails: true,
    logErrors: true,
    logErrorDetails: true
);

//Lancement app
$app->run();