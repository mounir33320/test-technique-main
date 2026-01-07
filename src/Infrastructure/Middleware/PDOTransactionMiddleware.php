<?php

namespace Aucoffre\Infrastructure\Middleware;

use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PDOTransactionMiddleware implements MiddlewareInterface
{
    public function __construct(private PDO $pdo)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->pdo->beginTransaction();

        try {
            $response = $handler->handle($request);
            $this->pdo->commit();

            return $response;
        } catch (\Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }
}