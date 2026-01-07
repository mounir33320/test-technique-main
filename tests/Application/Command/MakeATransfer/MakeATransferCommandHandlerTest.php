<?php

namespace Test\Application\Command\MakeATransfer;

use Aucoffre\Application\Command\MakeATransferCommand;
use Aucoffre\Application\Command\MakeATransferCommandHandler;
use Aucoffre\Domain\Entity\Account;
use Aucoffre\Domain\Exception\AccountNotFound;
use Aucoffre\Domain\Exception\CannotTransferToTheSameAccountException;
use Aucoffre\Domain\Exception\NegativeAmountException;
use Aucoffre\Domain\Exception\NegativeBalanceException;
use Aucoffre\Domain\ValueObject\Amount;
use PHPUnit\Framework\TestCase;
use Test\TestDouble\Fake\FakeAccountRepository;

class MakeATransferCommandHandlerTest extends TestCase
{
    const int ACCOUNT_ID_A = 1;
    const int ACCOUNT_ID_B = 2;
    private FakeAccountRepository $repository;
    private MakeATransferCommandHandler $handler;


    protected function setUp(): void
    {
        $this->repository = new FakeAccountRepository();
        $this->handler = new MakeATransferCommandHandler($this->repository);
    }

    public function testShouldTransfer10Euros(): void
    {
        $this->storeAccount(self::ACCOUNT_ID_A, 10);
        $this->storeAccount(self::ACCOUNT_ID_B, 0);

        $command = new MakeATransferCommand(self::ACCOUNT_ID_A, self::ACCOUNT_ID_B, 10);
        $this->handler->execute($command);

        $this->assertEquals(
            new Amount(0)->format(),
            $this->repository->accounts[self::ACCOUNT_ID_A]->jsonSerialize()['amount']
        );

        $this->assertEquals(
            new Amount(10)->format(),
            $this->repository->accounts[self::ACCOUNT_ID_B]->jsonSerialize()['amount']
        );
    }

    public function testShouldThrowErrorWhenNegativeAmount(): void
    {
        $this->storeAccount(self::ACCOUNT_ID_A, 0);
        $this->storeAccount(self::ACCOUNT_ID_B, 0);

        $command = new MakeATransferCommand(self::ACCOUNT_ID_A, self::ACCOUNT_ID_B, -1);
        $this->expectException(NegativeAmountException::class);
        $this->handler->execute($command);
    }

    public function testShouldThrowErrorWhenAccountIsNotFound(): void
    {
        $command = new MakeATransferCommand(self::ACCOUNT_ID_A, self::ACCOUNT_ID_B, -1);
        $this->expectException(AccountNotFound::class);
        $this->handler->execute($command);
    }

    public function testShouldThrowErrorWhenBalanceIsNegative(): void
    {
        $this->storeAccount(self::ACCOUNT_ID_A, 0);
        $this->storeAccount(self::ACCOUNT_ID_B, 0);

        $command = new MakeATransferCommand(self::ACCOUNT_ID_A, self::ACCOUNT_ID_B, 10);
        $this->expectException(NegativeBalanceException::class);
        $this->handler->execute($command);
    }

    public function testShouldThrowErrorWhenBothAccountIdsAreSame(): void
    {
        $this->storeAccount(self::ACCOUNT_ID_A, 0);
        $command = new MakeATransferCommand(self::ACCOUNT_ID_A, self::ACCOUNT_ID_A, 10);
        $this->expectException(CannotTransferToTheSameAccountException::class);
        $this->handler->execute($command);
    }

    private function storeAccount(int $id, int $amount): void
    {
        $account = new Account($id, new Amount($amount));
        $this->repository->accounts[$id] = $account;
    }
}