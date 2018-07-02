<?php
namespace InfraQL;

class Token
{
    private $numType;

    private $strText;

    public function __construct(int $numType = -1, string $strText = "")
    {
        $this->numType = $numType;
        $this->strText = $strText;
    }

    public function getNumType(): int
    {
        return $this->numType;
    }

    public function setNumType(int $numType): void
    {
        $this->numType = $numType;
    }

    public function getStrText(): string
    {
        return $this->strText;
    }

    public function setStrText(string $strText): void
    {
        $this->strText = $strText;
    }
}
