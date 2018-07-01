<?php
namespace InfraQL;

class Constantes
{
    const PARAMETRO_DEVE_USAR_DISTINCT = true;

    const CARACTER_DOIS_PONTOS = ":";

    const CARACTER_ASTERISCO = "*";

    const ER_OPERADORES_COMPARACAO_ESPERADOS = "=|<>";

    const ER_OPERADORES_LOGICOS_ESPERADOS = "OR|AND";

    const ER_CONDICAO = "/'?\b[^ ]{1,}\b'? {0,}(" . self::ER_OPERADORES_COMPARACAO_ESPERADOS . ") {0,}:?'?\b[^ ]{1,}\b'?/";

    const ER_CONDICAO_CAMPO = "/( |=){1,}.{0,}/";

    const ER_CONDICAO_VALOR = "/.{0,}( |=){1,}/";

    const ER_QUEBRAS_DE_LINHA = "/\r|\n/";

    const ER_ESPACOS_DUPLICADOS = "/ {1,}/";

    const ER_CONTEUDO_ANTES_DO_DTO = "/.{0,}FROM {1,}/";

    const ER_CONTEUDO_APOS_O_DTO = "/ {0,}WHERE.{1,}/";

    const ER_TUDO_QUE_NAO_FOR_CAMPO = "/SELECT (DISTINCT)?| FROM .{1,}/";

    const ER_EXCLUIR_ASPAS = "/^'|^\"|'$|\"$/";
}
