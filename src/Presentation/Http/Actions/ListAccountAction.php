<?php

namespace Aucoffre\Presentation\Http\Actions;

use Aucoffre\Domain\Repository\AccountRepositoryPort;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;

/**
 * Liste l'ensemble des comptes
 * @copyright ©2025 AuCOFFRE.com
 */
final readonly class ListAccountAction implements HttpAction
{
    /**
     * @param AccountRepositoryPort $accountRepo
     */
    public function __construct(
        private AccountRepositoryPort $accountRepo
    ) {
    }

    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        try {
            //Récupération de l'ensemble des comptes
            $accountGen = $this->accountRepo->getAll();
            //Construction du payload
            $payload = json_encode([
                'accounts' => iterator_to_array($accountGen)
            ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } catch (JsonException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        //Écriture dans la réponse
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->withStatus(200);
    }
}