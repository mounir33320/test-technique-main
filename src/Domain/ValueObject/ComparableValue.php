<?php

namespace Aucoffre\Domain\ValueObject;

/**
 * Classe abstraite utilitaire pour la comparaison de valeurs numériques
 * @copyright ©2025 AuCOFFRE.com
 */
abstract readonly class ComparableValue
{
    /**
     * @param float $value
     * @param int $scale Précision utilisée pour les comparaisons
     */
    public function __construct(
        public float $value,
        private int $scale = 2
    ) {
    }

    /**
     * Détermine si la valeur est plus petite
     * @param float $value
     * @return bool
     */
    public function lt(float $value): bool
    {
        return $this->norm($this->value) < $this->norm($value);
    }

    /**
     * Détermine si la valeur est plus petite ou égale
     * @param float $value
     * @return bool
     */
    public function lte(float $value): bool
    {
        return $this->norm($this->value) <= $this->norm($value);
    }

    /**
     * Détermine si la valeur est plus grande
     * @param float $value
     * @return bool
     */
    public function gt(float $value): bool
    {
        return $this->norm($this->value) > $this->norm($value);
    }

    /**
     * Détermine si la valeur est plus grande ou égale
     * @param float $value
     * @return bool
     */
    public function gte(float $value): bool
    {
        return $this->norm($this->value) >= $this->norm($value);
    }

    /**
     * Détermine si la valeur est égale
     * @param float $value
     * @return bool
     */
    public function eq(float $value): bool
    {
        return $this->norm($this->value) === $this->norm($value);
    }

    /**
     * @param float $v
     * @return float
     */
    protected function norm(float $v): float
    {
        return round($v, $this->scale);
    }
}