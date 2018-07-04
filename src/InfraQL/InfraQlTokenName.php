<?php
namespace InfraQL;

class InfraQlTokenName
{

    public const NAMES = array(
        InfraQlTokenType::BOF => "BOF",
        InfraQlTokenType::SELECT => "SELECT",
        InfraQlTokenType::FIELD_NAME => "FIELD_NAME",
        InfraQlTokenType::COMMA => "COMMA",
        InfraQlTokenType::FROM => "FROM",
        InfraQlTokenType::DTO_NAME => "DTO_NAME",
        InfraQlTokenType::WHERE => "WHERE",
        InfraQlTokenType::GREATER_THAN_OR_EQUAL_TO => "GREATER_THAN_OR_EQUAL_TO",
        InfraQlTokenType::NUMBER_INTEGER => "NUMBER_INTEGER",
        InfraQlTokenType::LOGICAL_OPERATOR_AND => "LOGICAL_OPERATOR_AND",
        InfraQlTokenType::PARENTHESES_LEFT => "PARENTHESES_LEFT",
        InfraQlTokenType::EQUAL => "EQUAL",
        InfraQlTokenType::USER_STRING => "USER_STRING",
        InfraQlTokenType::PARENTHESES_RIGHT => "PARENTHESES_RIGHT",
        InfraQlTokenType::ORDER_BY => "ORDER_BY",
        InfraQlTokenType::LOGICAL_OPERATOR_OR => "LOGICAL_OPERATOR_OR",
        InfraQlTokenType::GREATER_THAN => "GREATER_THAN",
        InfraQlTokenType::LESS_THAN => "LESS_THAN",
        InfraQlTokenType::LESS_THAN_OR_EQUAL_TO => "LESS_THAN_OR_EQUAL_TO",
        InfraQlTokenType::NOT_EQUAL_TO => "NOT_EQUAL_TO"
    );

    public static function getStrName(int $numTokenType): string
    {
        if (! isset(InfraQlTokenName::NAMES[$numTokenType])) {
            throw new \Exception(sprintf("Nao ha nome definido para o tipo de token '%s'.", $numTokenType));
        }
        return InfraQlTokenName::NAMES[$numTokenType];
    }
}

