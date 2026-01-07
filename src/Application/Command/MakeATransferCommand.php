<?php

namespace Aucoffre\Application\Command;

readonly class MakeATransferCommand
{
    public function __construct(
        public int $fromAccountId,
        public int $toAccountId,
        public float $amount,
    )
    {
    }
}