<?php

namespace Aucoffre\Infrastructure\Middleware;

use Aucoffre\Infrastructure\Exception\UnauthorizedException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ApiKeyAuthMiddleware implements MiddlewareInterface
{
    const string API_KEY = 'X-API-KEY';

    public function __construct(private readonly string $apiKey)
    {
    }

    /**
     * @throws UnauthorizedException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $apiKey = $request->getHeaderLine(self::API_KEY) ?: null;

        if (null === $apiKey || ($apiKey !== $this->apiKey)) {
            $detail = null === $apiKey ? 'You must provide an API key.' : 'You provided an invalid API key.';
            throw new UnauthorizedException($detail);
        }

        return $handler->handle($request);
    }
}