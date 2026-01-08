<?php

namespace Aucoffre\Domain\ValueObject;

use Aucoffre\Domain\Exception\NegativeAmountException;
use Aucoffre\Domain\Exception\NegativeBalanceException;
use Locale;
use NumberFormatter;

/**
 * Représente un montant
 * @copyright ©2025 AuCOFFRE.com
 */
final readonly class Amount
{
    /**
     * @param float $value La valeur du prix
     */
    private function __construct(
        private float $value
    ) {
    }

    public static function fromFloat(float $value): self
    {
        if ($value < 0) {
            throw new NegativeAmountException(
                'error.amount.invalid_amount',
                'Invalid amount',
                sprintf("Amount \"%s\" must not be negative.", $value)
            );
        }

        return new self($value);
    }

    /**
     * @param float $value
     * @return Amount
     */
    public function add(float $value): Amount
    {
        return new Amount($this->value + $value);
    }

    /**
     * @param float $value
     * @return Amount
     */
    public function subtract(float $value): Amount
    {
        if ($value > $this->value) {
            throw new NegativeBalanceException(
                'error.account.negative_balance',
                'Negative balance',
                "Amount must not be less than balance: {$this->value}"
            );
        }

        return new Amount($this->value - $value);
    }

    /**
     * Formatte en fonction de la locale
     * @return string 
     */
    public function format(): string
    {
        $formatter = new NumberFormatter(Locale::getDefault(), NumberFormatter::CURRENCY);
        return $formatter->formatCurrency($this->value, 'EUR');
    }

    /**
     * Récupère la valeur en plus petite unité (par exemple centimes).
     * @return int La valeur dans la plus petite unité.
     */
    public function toMinorUnit(): int
    {
        $formatter = new NumberFormatter(Locale::getDefault(), NumberFormatter::CURRENCY);
        $fractionDigits = $formatter->getAttribute(NumberFormatter::FRACTION_DIGITS);
        $factor = pow(10, $fractionDigits);
        return (int)round($this->value * $factor);
    }
}