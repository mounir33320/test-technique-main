<?php

namespace Aucoffre\Presentation\Http\Actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Contrat pour les actions Http
 * @copyright ©2025 AuCOFFRE.com
 */
interface HttpAction
{
    /**
     * Exécution de l'action
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface;
}