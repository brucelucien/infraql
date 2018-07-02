<?php
namespace InfraQL;

abstract class Lexer
{

    public const EOF = "\0";

    public const EOF_TYPE = 1;

    private $strInput;

    private $strCharacter;

    private $numPosition = 0;

    abstract function getObjNextToken(): Token;

    public function __construct(string $strInput)
    {
        $this->strInput = $strInput;
        $this->strCharacter = substr($this->strInput, $this->numPosition, 1);
    }

    public function getStrInput(): string
    {
        return $this->strInput;
    }

    public function getStrCharacter(): string
    {
        return $this->strCharacter;
    }

    public function getNumPosition(): int
    {
        return $this->numPosition;
    }

    public function consume(): void
    {
        $this->numPosition ++;
        if ($this->numPosition >= strlen($this->strInput)) {
            $this->strCharacter = self::EOF;
        } else {
            $this->strCharacter = substr($this->strInput, $this->numPosition, 1);
        }
    }

    public function match(string $strX): void
    {
        if ($this->strCharacter == $strX) {
            $this->consume();
        } else {
            throw new \Exception("Esperava o caractere '{$this->strCharacter}' mas obteve o caractere '{$strX}'.");
        }
    }
}
