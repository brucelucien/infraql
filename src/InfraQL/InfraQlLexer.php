<?php
namespace InfraQL;

class InfraQlLexer extends Lexer
{
    public const SELECT = 2;

    public const FIELD_NAME = 3;

    public const COMMA = 4;

    public const FROM = 5;

    public const DTO_NAME = 6;

    public const BOF = 7;

    public const WHERE = 8;

    public const GREATER_THAN_OR_EQUAL_TO = 9;

    public const NUMBER_INTEGER = 10;

    private $objPreviousToken = null;

    public function getObjNextToken(): Token
    {
        $objToken = null;
        while (($this->getStrCharacter() != self::EOF) && ($objToken == null)) {
            switch ($this->getStrCharacter()) {
                case ' ':
                    $this->consume();
                    continue;
                    break;
                case ',':
                    $objToken = new Token(self::COMMA, $this->getStrCharacter());
                    $this->consume();
                    break;
                case '>':
                    $this->consume();
                    if ($this->getStrCharacter() == "=") {
                        $objToken = new Token(self::GREATER_THAN_OR_EQUAL_TO, ">=");
                    }
                    $this->consume();
                    break;
                default:
                    if ($this->isNumber()) {
                        $strNumber = "";
                        do {
                            $strNumber .= $this->getStrCharacter();
                            $this->consume();
                        } while ($this->isNumber());
                        $objToken = new Token(self::NUMBER_INTEGER, $strNumber);
                    }
                    if ($this->isLetter()) {
                        $strText = "";
                        do {
                            $strText .= $this->getStrCharacter();
                            $this->consume();
                        } while ($this->isLetter());
                        switch ($strText) {
                            case 'SELECT':
                                $objToken = new Token(self::SELECT, $strText);
                                break;
                            case 'FROM':
                                $objToken = new Token(self::FROM, $strText);
                                break;
                            case 'WHERE':
                                $objToken = new Token(self::WHERE, $strText);
                                break;
                            default:
                                if ($this->objPreviousToken->getNumType() == self::FROM) {
                                    $objToken = new Token(self::DTO_NAME, $strText);
                                } else {
                                    $objToken = new Token(self::FIELD_NAME, $strText);
                                }
                                break;
                        }
                    }
                    break;
            }
        }
        if (is_null($objToken)) {
            $objToken = new Token(self::EOF_TYPE, "");
        }
        $this->objPreviousToken = new Token($objToken->getNumType(), $objToken->getStrText());
        return $objToken;
    }

    public function getStrTokenName(int $numTokenType): string
    {
        return "";
    }

    public function isLetter(): bool
    {
        return (
            (
                ord($this->getStrCharacter()) >= 65
                && ord($this->getStrCharacter()) <= 90
            )
            || (
                ord($this->getStrCharacter()) >= 97
                && ord($this->getStrCharacter()) <= 122
            )
        );
    }

    public function isNumber(): bool
    {
        return (
            ord($this->getStrCharacter()) >= 48
            && ord($this->getStrCharacter()) <= 57
        );
    }

    public function getObjPreviousToken(): Token
    {
        if (is_null($this->objPreviousToken)) {
            return new Token(self::BOF, "");
        } else {
            return $this->objPreviousToken;
        }
    }
}
