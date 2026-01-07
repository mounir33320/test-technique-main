<?php

namespace Aucoffre\Domain\Exception;

class NegativeAmountException extends \Exception implements DomainException
{
    private string $detail;

    public function __construct(private string $type, private string $title, string $detail, int $code = 400)
    {
        $this->detail = $detail;
        parent::__construct($detail, $code);
    }

    public function type(): string
    {
        return $this->type;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function detail(): string
    {
        return $this->detail;
    }
}