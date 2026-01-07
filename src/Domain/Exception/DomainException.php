<?php

namespace Aucoffre\Domain\Exception;

interface DomainException
{
    public function type(): string;
    public function title(): string;
    public function detail(): string;
}