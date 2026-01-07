<?php

/**
 * Bootstrap de l'application
 * Chargement autoload, l'env, et construction container di
 * @copyright ©2025 AuCOFFRE.com
 */

declare(strict_types=1);

use DI\ContainerBuilder;
use Dotenv\Dotenv;

const ROOT_PATH = __DIR__;

//Autoload Composer
require ROOT_PATH . '/vendor/autoload.php';

//Chargement du .env
$dotenv = Dotenv::createImmutable(ROOT_PATH);
$dotenv->safeLoad();

//Construction du container
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(ROOT_PATH . '/config/dependencies.php');
return $containerBuilder->build();