<?php
namespace InfraQL;

abstract class Parser
{

    private const ERROR_MATCH_MESSAGE_FORMAT = "Era esperado '%s' mas foi encontrado '%s'.";

    private $objInput;

    private $objLookahead;

    public function __construct(Lexer $objInput)
    {
        $this->objInput = $objInput;
        $this->consume();
    }

    public function getObjInput(): Lexer
    {
        return $this->objInput;
    }

    public function getObjLookahead(): Token
    {
        return $this->objLookahead;
    }

    public function consume(): void
    {
        $this->objLookahead = $this->objInput->getObjNextToken();
    }

    public function match(int $numX)
    {
        if ($this->objLookahead->getNumType() == $numX) {
            $this->consume();
        } else {
            $strErrorMessage = sprintf(self::ERROR_MATCH_MESSAGE_FORMAT, InfraQlTokenName::getStrName($numX), InfraQlTokenName::getStrName($this->objLookahead->getNumType()));
            throw new \Exception($strErrorMessage);
        }
    }
}

