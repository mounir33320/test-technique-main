<?php

namespace Test\TestDouble\Fake;

use Aucoffre\Domain\Entity\Account;
use Aucoffre\Domain\Repository\AccountRepositoryPort;
use Generator;

class FakeAccountRepository implements AccountRepositoryPort
{
    /**
     * @var Account[]
     */
    public array $accounts = [];
    public function getAll(): Generator
    {
        foreach ($this->accounts as $account) {
            yield $account;
        }
    }

    public function findOneById(int $accountId): ?Account
    {
        return $this->accounts[$accountId] ?? null;
    }

    public function update(Account $account): void
    {
        $this->accounts[$account->jsonSerialize()['id']] = $account;
    }
}