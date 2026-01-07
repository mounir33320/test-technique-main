<?php

namespace Aucoffre\Domain\Entity;

use Aucoffre\Domain\Exception\NegativeAmountException;
use Aucoffre\Domain\ValueObject\Amount;
use JsonSerializable;

/**
 * Représentation d'un compte client
 * @copyright ©2025 AuCOFFRE.com
 */
final readonly class Account implements JsonSerializable
{
    /**
     * @param int $id
     * @param Amount $balance
     */
    public function __construct(
        private int $id,
        private Amount $balance
    ) {
    }

    public function withDraw(float $amount): self
    {
        if ($amount < 0) {
            throw new NegativeAmountException(
                'error.amount.invalid_amount',
                'Invalid amount',
                sprintf("Amount \"%s\" must not be negative.", $amount)
            );
        }

        $balance = $this->balance->subtract($amount);


        return new self($this->id, $balance);
    }

    public function deposit(int $amount): self
    {
        return new self($this->id, $this->balance->add($amount));
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->balance->format(),
            'balanceToMinorUnit' => $this->balance->toMinorUnit(),
        ];
    }
}