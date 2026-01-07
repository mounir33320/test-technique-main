<?php

namespace Aucoffre\Application\Command;

use Aucoffre\Domain\Exception\AccountNotFound;
use Aucoffre\Domain\Exception\CannotTransferToTheSameAccountException;
use Aucoffre\Domain\Exception\NegativeAmountException;
use Aucoffre\Domain\Repository\AccountRepositoryPort;

class MakeATransferCommandHandler
{
    public function __construct(private readonly AccountRepositoryPort $repository)
    {
    }

    /**
     * @throws NegativeAmountException
     * @throws CannotTransferToTheSameAccountException
     * @throws AccountNotFound
     */
    public function execute(MakeATransferCommand $command): void
    {
        if ($command->fromAccountId === $command->toAccountId) {
            throw new CannotTransferToTheSameAccountException(
                'error.account.invalid_account_transfer',
                'Invalid account transfer',
                sprintf(
                    "Impossible to transfer to the same account with the same ID '%s'",
                    $command->toAccountId
                )
            );
        }

        $fromAccount = $this->repository->findOneById($command->fromAccountId);
        if (!$fromAccount) {
            $this->throwAccountNotFound($command->fromAccountId);
        }
        $fromAccount = $fromAccount->withDraw($command->amount);

        $toAccount = $this->repository->findOneById($command->toAccountId);

        if (!$toAccount) {
            $this->throwAccountNotFound($command->toAccountId);
        }

        $toAccount = $toAccount->deposit($command->amount);
        $this->repository->update($fromAccount);
        $this->repository->update($toAccount);
    }

    /**
     * @throws AccountNotFound
     */
    private function throwAccountNotFound(int $id): void
    {
        throw new AccountNotFound(
            'error.account.not_found',
            'Account not found',
            sprintf("Account with id '%s' not found.", $id)
        );
    }
}