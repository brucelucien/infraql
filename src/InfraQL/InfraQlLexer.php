<?php
namespace InfraQL;

class InfraQlLexer extends Lexer
{

    private $objPreviousToken = null;

    private $numContextClause = - 1;

    public function __construct(string $strInput)
    {
        parent::__construct($strInput);
        $this->objPreviousToken = new Token(InfraQlTokenType::BOF, "");
    }

    public function getObjNextToken(): Token
    {
        $objToken = null;
        if ($this->getStrCharacter() == self::EOF) {
            $objToken = new Token(self::EOF_TYPE, "");
        } else {
            while (($this->getStrCharacter() != self::EOF) && ($objToken == null)) {
                switch ($this->getStrCharacter()) {
                    case " ":
                    case "\r":
                    case "\n":
                    case "\t":
                        $this->consume();
                        continue;
                    case ",":
                        $objToken = new Token(InfraQlTokenType::COMMA, $this->getStrCharacter());
                        $this->consume();
                        break;
                    case "(":
                        $objToken = new Token(InfraQlTokenType::PARENTHESES_LEFT, $this->getStrCharacter());
                        $this->consume();
                        break;
                    case ")":
                        $objToken = new Token(InfraQlTokenType::PARENTHESES_RIGHT, $this->getStrCharacter());
                        $this->consume();
                        break;
                    case "=":
                        $objToken = new Token(InfraQlTokenType::EQUAL, $this->getStrCharacter());
                        $this->consume();
                        break;
                    case "'":
                        $strUserString = "";
                        do {
                            $strUserString .= $this->getStrCharacter();
                            $this->consume();
                        } while ($this->getStrCharacter() != "'");
                        $strUserString .= $this->getStrCharacter();
                        $objToken = new Token(InfraQlTokenType::USER_STRING, $strUserString);
                        $this->consume();
                        break;
                    case ">":
                        $this->consume();
                        if ($this->getStrCharacter() == "=") {
                            $objToken = new Token(InfraQlTokenType::GREATER_THAN_OR_EQUAL_TO, ">=");
                        } else {
                            $objToken = new Token(InfraQlTokenType::GREATER_THAN, ">");
                        }
                        $this->consume();
                        break;
                    case "<":
                        $this->consume();
                        switch ($this->getStrCharacter()) {
                            case "=":
                                $objToken = new Token(InfraQlTokenType::LESS_THAN_OR_EQUAL_TO, "<=");
                                break;
                            case ">":
                                $objToken = new Token(InfraQlTokenType::NOT_EQUAL_TO, "<>");
                                break;
                            default:
                                $objToken = new Token(InfraQlTokenType::LESS_THAN, "<");
                                break;
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
                            $objToken = new Token(InfraQlTokenType::NUMBER_INTEGER, $strNumber);
                        }
                        if ($this->isLetter()) {
                            $strText = "";
                            do {
                                $strText .= $this->getStrCharacter();
                                $this->consume();
                            } while ($this->isLetter());
                            switch ($strText) {
                                case "SELECT":
                                    $this->numContextClause = InfraQlTokenType::SELECT;
                                    $objToken = new Token(InfraQlTokenType::SELECT, $strText);
                                    break;
                                case "FROM":
                                    $this->numContextClause = InfraQlTokenType::FROM;
                                    $objToken = new Token(InfraQlTokenType::FROM, $strText);
                                    break;
                                case "WHERE":
                                    $this->numContextClause = InfraQlTokenType::WHERE;
                                    $objToken = new Token(InfraQlTokenType::WHERE, $strText);
                                    break;
                                case "AND":
                                    $objToken = new Token(InfraQlTokenType::LOGICAL_OPERATOR_AND, $strText);
                                    break;
                                case "OR":
                                    $objToken = new Token(InfraQlTokenType::LOGICAL_OPERATOR_OR, $strText);
                                    break;
                                case "ORDER":
                                    $strText .= $this->getStrCharacter();
                                    $this->consume();
                                    $strText .= $this->getStrCharacter();
                                    $this->consume();
                                    $strText .= $this->getStrCharacter();
                                    $this->consume();
                                    $this->numContextClause = InfraQlTokenType::ORDER_BY;
                                    $objToken = new Token(InfraQlTokenType::ORDER_BY, $strText);
                                    break;
                                default:
                                    if ($this->numContextClause == InfraQlTokenType::FROM) {
                                        $objToken = new Token(InfraQlTokenType::DTO_NAME, $strText);
                                    } else {
                                        if (($this->numContextClause == InfraQlTokenType::SELECT) || ($this->numContextClause == InfraQlTokenType::WHERE) || ($this->numContextClause == InfraQlTokenType::ORDER_BY)) {
                                            $objToken = new Token(InfraQlTokenType::FIELD_NAME, $strText);
                                        }
                                    }
                                    if (is_null($objToken)) {
                                        throw new \Exception("O comando InfraQL contem texto nao previsto na linguagem de consulta. Comando analisado: [{$this->getStrInput()}].");
                                    }
                                    break;
                            }
                        }
                        if (is_null($objToken)) {
                            throw new \Exception("O caractere {$this->getStrCharacter()} nao esta previsto na linguagem de consulta. Comando analisado: [{$this->getStrInput()}].");
                        }
                        break;
                }
            }
        }
        $this->objPreviousToken = new Token($objToken->getNumType(), $objToken->getStrText());
        return $objToken;
    }

    public function isLetter(): bool
    {
        return ((ord($this->getStrCharacter()) >= 65 && ord($this->getStrCharacter()) <= 90) || (ord($this->getStrCharacter()) >= 97 && ord($this->getStrCharacter()) <= 122));
    }

    public function isNumber(): bool
    {
        return (ord($this->getStrCharacter()) >= 48 && ord($this->getStrCharacter()) <= 57);
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
