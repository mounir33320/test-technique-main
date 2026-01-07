<?php

namespace Aucoffre\Presentation\Http\Actions;

use Aucoffre\Application\Command\MakeATransferCommand;
use Aucoffre\Application\Command\MakeATransferCommandHandler;
use Aucoffre\Domain\Exception\DomainException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class MakeATransferAction implements HttpAction
{
    public function __construct(
        private readonly MakeATransferCommandHandler $commandHandler
    ) {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $fromAccountId = $args['id'] ?? null;
        $toAccountId = $request->getParsedBody()['toAccountId'] ?? null;
        $amount = $request->getParsedBody()['amount'] ?? null;

        if (!$fromAccountId || !$toAccountId || !$amount) {
            throw new \InvalidArgumentException('Missing required fields');
        }

        $command = new MakeATransferCommand($fromAccountId, $toAccountId, $amount);

        $this->commandHandler->execute($command);

        return $response->withStatus(204);
    }
}