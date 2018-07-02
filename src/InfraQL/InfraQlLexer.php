<?php
namespace InfraQL;

class InfraQlLexer extends Lexer
{
    public const BOF = 2;

    public const SELECT = 3;

    public const FIELD_NAME = 4;

    public const COMMA = 5;

    public const FROM = 6;

    public const DTO_NAME = 7;

    public const WHERE = 8;

    public const GREATER_THAN_OR_EQUAL_TO = 9;

    public const NUMBER_INTEGER = 10;

    public const LOGICAL_OPERATOR_AND = 11;

    public const PARENTHESES_LEFT = 12;

    private $objPreviousToken = null;

    private $numContextClause = -1;

    public function __construct(string $strInput)
    {
        parent::__construct($strInput);
        $this->objPreviousToken = new Token(self::BOF, "");
    }

    public function getObjNextToken(): Token
    {
        $objToken = null;
        if ($this->getStrCharacter() == self::EOF) {
            $objToken = new Token(self::EOF_TYPE, "");
        } else {
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
                    case '(':
                        $objToken = new Token(self::PARENTHESES_LEFT, $this->getStrCharacter());
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
                                case 'AND':
                                    $objToken = new Token(self::LOGICAL_OPERATOR_AND, $strText);
                                    break;
                                default:
                                    if ($this->numContextClause == self::FROM) {
                                        $objToken = new Token(self::DTO_NAME, $strText);
                                    } else {
                                        switch ($this->numContextClause) {
                                            case self::SELECT:
                                            case self::WHERE:
                                                $objToken = new Token(self::FIELD_NAME, $strText);
                                                break;
                                        }
                                    }
                                    if (is_null($objToken)) {
                                        throw new \Exception("O comando InfraQL contem texto nao previsto na linguagem de consulta. Comando analisado: [{$this->getStrInput()}].");
                                    }
                                    break;
                            }
                        }
                        break;
                }
            }
        }
        switch ($objToken->getNumType()) {
            case self::SELECT:
                $this->numContextClause = self::SELECT;
                break;
            case self::FROM:
                $this->numContextClause = self::FROM;
                break;
            case self::WHERE:
                $this->numContextClause = self::WHERE;
                break;
        }
        $this->objPreviousToken = new Token($objToken->getNumType(), $objToken->getStrText());
        return $objToken;
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
        return $this->objPreviousToken;
    }

    public function getNumContextClause()
    {
        return $this->numContextClause;
    }
}
