<?php

use Aucoffre\Domain\Repository\AccountRepositoryPort;
use Aucoffre\Infrastructure\Repository\SqliteAccountRepository;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Symfony\Component\HttpClient\Psr18Client;

use function DI\autowire;
use function DI\create;
use function DI\get;

return [
    //Chemin vers base SQLite
    'db.path' => function (): string {
        $dbEnv = $_ENV['DATABASE_PATH'] ?? throw new RuntimeException('DATABASE_PATH non défini dans .env');
        return ROOT_PATH . '/' . $dbEnv;
    },
    //Chemin vers base de test SQLite
    'db_test.path' => function (): string {
        $dbEnv = $_ENV['DATABASE_TEST_PATH'] ?? throw new RuntimeException('DATABASE_TEST_PATH non défini dans .env');
        return ROOT_PATH . '/' . $dbEnv;
    },
    'api_key' => function (): string {
        return $_ENV['API_KEY'] ?? throw new RuntimeException('API_KEY is not defined');
    },

    //Instance PDO centralisée
    PDO::class => function (ContainerInterface $c): PDO {
        $dbPath = $c->get('db.path');
        $pdo = new PDO("sqlite:$dbPath");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    },
    //Instance PDO centralisée
    'pdo_test' => function (ContainerInterface $c): PDO {
        $dbPath = $c->get('db_test.path');
        $pdo = new PDO("sqlite:$dbPath");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    },
    
    /**
     * Factories PSR17
     * Utilisation de la factory de Nyholm
     * @see https://github.com/Nyholm/psr7
     */
    Psr17Factory::class => create(),
    ResponseFactoryInterface::class => get(Psr17Factory::class),
    StreamFactoryInterface::class => get(Psr17Factory::class),
    UploadedFileFactoryInterface::class => get(Psr17Factory::class),
    UriFactoryInterface::class => get(Psr17Factory::class),
    ServerRequestFactoryInterface::class => get(Psr17Factory::class),
    RequestFactoryInterface::class => get(Psr17Factory::class),
    ClientInterface::class => create(Psr18Client::class)
        ->constructor(
            null,
            get(ResponseFactoryInterface::class),
            get(StreamFactoryInterface::class)
        ),

    //Repositories
    AccountRepositoryPort::class => autowire(SqliteAccountRepository::class),
];