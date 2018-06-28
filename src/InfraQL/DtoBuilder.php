<?php
namespace InfraQL;

class DtoBuilder
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

    private $infraQuery = "";

    private $strNomeDto = "";

    private $arrCamposARetornar = array();

    private $arrParametrosInformados = array();

    private $arrCamposCondicao = array();

    private $arrOperadoresComparacao = array();

    private $arrValoresCondicao = array();

    private $arrOperadoresLogicos = array();

    private function retirarDaQueryEspacosAdicionaisEQuebrasDeLinha()
    {
        $querySemQuebrasDeLinha = preg_replace(self::ER_QUEBRAS_DE_LINHA, "", $this->infraQuery);
        $querySemEspacosAdicionais = preg_replace(self::ER_ESPACOS_DUPLICADOS, " ", $querySemQuebrasDeLinha);
        $this->infraQuery = $querySemEspacosAdicionais;
    }

    private function extrairNomeDto()
    {
        $this->strNomeDto = preg_replace(self::ER_CONTEUDO_ANTES_DO_DTO, " ", $this->infraQuery);
        $this->strNomeDto = preg_replace(self::ER_CONTEUDO_APOS_O_DTO, " ", $this->strNomeDto);
    }

    private function extrairCamposARetornar()
    {
        $strCamposARetornar = preg_replace(self::ER_TUDO_QUE_NAO_FOR_CAMPO, " ", $this->infraQuery);
        $strCamposARetornar = trim($strCamposARetornar);
        $this->arrCamposARetornar = explode(",", $strCamposARetornar);
        $this->arrCamposARetornar = array_map(function ($campo) {return trim($campo);}, $this->arrCamposARetornar);
    }

    private function extrairCondicoesWhere()
    {
        $this->arrCamposCondicao = array();
        $this->arrValoresCondicao = array();
        $arrCondicoes = null;
        preg_match_all(self::ER_CONDICAO, $this->infraQuery, $arrCondicoes);
        foreach ($arrCondicoes[0] as $condicao) {
            $strCampoCondicao = trim(preg_replace(self::ER_CONDICAO_CAMPO, "", $condicao));
            $strValorCondicao = trim(preg_replace(self::ER_CONDICAO_VALOR, "", $condicao));
            $strOperadorComparacao = trim(preg_replace("/{$strCampoCondicao}|{$strValorCondicao}/", "", $condicao));
            $this->arrCamposCondicao[] = substr($strCampoCondicao, 3);
            if (strpos($strValorCondicao, self::CARACTER_DOIS_PONTOS) === 0) {
                $strValorCondicao = $this->arrParametrosInformados[substr($strValorCondicao, 1)];
            }
            $this->arrValoresCondicao[] = preg_replace(self::ER_EXCLUIR_ASPAS, "", $strValorCondicao);
        }
        preg_match_all("/" . self::ER_OPERADORES_LOGICOS_ESPERADOS . "/", $this->infraQuery, $this->arrOperadoresLogicos);
        $this->arrOperadoresLogicos = $this->arrOperadoresLogicos[0];
        preg_match_all("/" . self::ER_OPERADORES_COMPARACAO_ESPERADOS . "/", $this->infraQuery, $this->arrOperadoresComparacao);
        $this->arrOperadoresComparacao = $this->arrOperadoresComparacao[0];
    }

    private function clausulaWhereFoiInformada()
    {
        return strpos($this->infraQuery, " WHERE ");
    }

    private function distinctFoiInformado()
    {
        return strpos($this->infraQuery, "SELECT DISTINCT");
    }

    private function adicionarAoDtoCondicoesNaClausulaWhere($dto)
    {
        if ($this->clausulaWhereFoiInformada()) {
            $this->extrairCondicoesWhere();
            if (sizeof($this->arrOperadoresLogicos) > 0) {
                $dto->adicionarCriterio($this->arrCamposCondicao, $this->arrOperadoresComparacao, $this->arrValoresCondicao, $this->arrOperadoresLogicos);
            } else {
                $dto->adicionarCriterio($this->arrCamposCondicao, $this->arrOperadoresComparacao, $this->arrValoresCondicao);
            }
        }
    }

    public function __construct($strInfraQuery)
    {
        $this->infraQuery = $strInfraQuery;
        $this->retirarDaQueryEspacosAdicionaisEQuebrasDeLinha();
        $this->extrairNomeDto();
        $this->extrairCamposARetornar();
    }

    public function setParam($strNomeParametro, $varValorQualquer)
    {
        if (strpos($strNomeParametro, self::CARACTER_DOIS_PONTOS) === 0) {
            $this->arrParametrosInformados[substr($strNomeParametro, 1)] = $varValorQualquer;
        } else {
            $this->arrParametrosInformados[$strNomeParametro] = $varValorQualquer;
        }
    }

    public function gerarDto()
    {
        $objDto = null;
        eval('$objDto = new ' . $this->strNomeDto . '();');
        if ($this->distinctFoiInformado()) {
            $objDto->setDistinct(self::PARAMETRO_DEVE_USAR_DISTINCT);
        }
        foreach ($this->arrCamposARetornar as $strCampo) {
            if ($strCampo == self::CARACTER_ASTERISCO) {
                $objDto->retTodos();
            } else {
                eval('$objDto->ret' . $strCampo . '();');
            }
        }
        $this->adicionarAoDtoCondicoesNaClausulaWhere($objDto);
        return $objDto;
    }
}
