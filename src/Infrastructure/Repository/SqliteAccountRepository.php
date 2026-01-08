<?php

namespace Aucoffre\Infrastructure\Repository;

use Aucoffre\Domain\Entity\Account;
use Aucoffre\Domain\Exception\NegativeAmountException;
use Aucoffre\Domain\Repository\AccountRepositoryPort;
use Aucoffre\Domain\ValueObject\Amount;
use Generator;
use RuntimeException;

/**
 * Implémentation gestion des comptes avec SQLite
 * @copyright ©2025 AuCOFFRE.com
 */
final readonly class SqliteAccountRepository extends SqliteRepository implements AccountRepositoryPort
{
    /**
     * @inheritDoc
     * @throws NegativeAmountException
     */
    public function getAll(): Generator
    {
        $statement = $this->pdo->query('SELECT acc_id, acc_balance FROM account ORDER BY acc_id');
        while ($row = $statement->fetch()) {
            yield new Account(
                (int)$row['acc_id'],
                Amount::fromFloat((int)$row['acc_balance'] / 100)
            );
        }
    }

    /**
     * @throws NegativeAmountException
     */
    public function findOneById(int $accountId): ?Account
    {
        $statement = $this->pdo->prepare('SELECT acc_id, acc_balance FROM account WHERE acc_id = ?');
        $statement->execute([$accountId]);
        $row = $statement->fetch();
        if (!$row) {
            return null;
        }
        return new Account($row['acc_id'], Amount::fromFloat($row['acc_balance'] / 100));
    }

    public function update(Account $account): void
    {
        $accBalance = $account->jsonSerialize()['balanceToMinorUnit'];
        $statement = $this->pdo->prepare("UPDATE account SET acc_balance = ? WHERE acc_id = ?");
        $statement->execute([
            $accBalance,
            $account->jsonSerialize()['id']
        ]);
    }
}
