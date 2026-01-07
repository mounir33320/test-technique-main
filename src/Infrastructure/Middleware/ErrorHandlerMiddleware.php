<?php

namespace Aucoffre\Infrastructure\Middleware;

use Aucoffre\Domain\Exception\DomainException;
use Aucoffre\Infrastructure\Exception\UnauthorizedException;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpBadRequestException;

class ErrorHandlerMiddleware implements MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);

        } catch (DomainException $e) {
            return $this->problem(
                $e->getCode() ?? 400,
                $e->type(),
                $e->title(),
                $e->detail()
            );

        } catch (HttpBadRequestException $e) {
            return $this->problem(
                400,
                'error.bad_request',
                'Bad Request',
                $e->getMessage()
            );

        } catch (UnauthorizedException $e) {
            return $this->problem(
                401,
                'error.unauthorized',
                'Unauthorized',
                $e->getMessage()
            );
        }
        catch (\Throwable $e) {
            return $this->problem(
                500,
                'error.unknown',
                'Internal Server Error',
                'An unexpected error occurred.'
            );
        }
    }

    private function problem(int $status, string $type, string $title, string $detail): ResponseInterface
    {
        $response = new Response($status);
        $response = $response->withHeader('Content-Type', 'application/problem+json; charset=utf-8');

        $payload = [
            'type' => $type,
            'title' => $title,
            'status' => $status,
            'detail' => $detail,
        ];

        $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        return $response;
    }
}