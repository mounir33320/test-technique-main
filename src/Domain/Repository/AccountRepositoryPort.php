<?php

namespace Aucoffre\Domain\Repository;

use Aucoffre\Domain\Entity\Account;
use Generator;

/**
 * Interface repo pour gestion des comptes
 * @copyright ©2025 AuCOFFRE.com
 */
interface AccountRepositoryPort
{
    /**
     * Récupération de l'ensemble des comptes clients existants
     * @return Generator<Account>
     */
    public function getAll(): Generator;

    public function findOneById(int $accountId): ?Account;

    public function update(Account $account): void;
}