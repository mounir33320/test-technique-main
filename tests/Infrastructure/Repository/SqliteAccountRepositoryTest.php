<?php

namespace Test\Infrastructure\Repository;

use Aucoffre\Domain\Entity\Account;
use Aucoffre\Domain\ValueObject\Amount;
use Aucoffre\Infrastructure\Repository\SqliteAccountRepository;
use DI\Container;
use PDO;
use PHPUnit\Framework\TestCase;



class SqliteAccountRepositoryTest extends TestCase
{

    private Container $container;
    /**
     * @var PDO
     */
    private mixed $pdo;

    protected function setUp(): void
    {
        $this->container = require dirname(__DIR__, 3) . '/bootstrap.php';
        $this->pdo = $this->container->get('pdo_test');
        $this->repository = new SqliteAccountRepository($this->pdo);

        $this->pdo->exec('DELETE FROM account');
    }

    public function testFindOneById(): void
    {
        $accountId = 1;
        $balanceInMinorUnit = 100;
        $balance = $balanceInMinorUnit / 100;
        $sql = "INSERT INTO account (acc_id, acc_balance) VALUES ($accountId, $balanceInMinorUnit)";
        $this->pdo->exec($sql);

        $expectedAccount = new Account($accountId, Amount::fromFloat($balance));
        $actualAccount = $this->repository->findOneById($accountId);
        $this->assertEquals($expectedAccount, $actualAccount);
    }

    public function testUpdateAccount(): void
    {
        $accountId = 1;
        $balanceInMinorUnit = 1000;
        $balance = $balanceInMinorUnit / 100;
        $account = new Account($accountId, Amount::fromFloat($balance));

        $sql = "INSERT INTO account (acc_id, acc_balance) VALUES ($accountId, $balanceInMinorUnit)";
        $this->pdo->exec($sql);


        $updatedAccount = $account->withDraw(10);
        $this->repository->update($updatedAccount);

        $expectedAccount = new Account($accountId, Amount::fromFloat(0));
        $fetchedAccount = $this->repository->findOneById($accountId);
        $this->assertEquals($expectedAccount, $fetchedAccount);
    }
}