<?php

namespace Aucoffre\Presentation\Http\Actions;

use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;

/**
 * Say hello !
 * @copyright ©2025 AuCOFFRE.com
 */
final readonly class SayHelloAction implements HttpAction
{
    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        try {
            $jsonData = json_encode(
                value: [
                    'status' => 'ok',
                    'message' => 'Salut ! Bienvenue au test technique AuCOFFRE.',
                ],
                flags: JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT
            );
            $response->getBody()->write($jsonData);
            return $response->withHeader('Content-Type', 'application/json;charset=utf-8')->withStatus(200);
        } catch (JsonException $ex) {
            throw new RuntimeException($ex->getMessage(), $ex->getCode(), $ex);
        }
    }
}