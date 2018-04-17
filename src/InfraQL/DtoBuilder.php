<?php
namespace InfraQL;

class DtoBuilder
{

    const DEVE_USAR_DISTINCT = true;

    const ER_CONDICAO = "/'?\b[^ ]{1,}\b'? {0,}= {0,}:?'?\b[^ ]{1,}\b'?/";

    const ER_CONDICAO_CAMPO = "/( |=){1,}.{0,}/";

    const ER_CONDICAO_VALOR = "/.{0,}( |=){1,}/";

    const ER_QUEBRAS_DE_LINHA = "/\r|\n/";

    const ER_ESPACOS_DUPLICADOS = "/ {1,}/";

    const ER_CONTEUDO_ANTES_DO_DTO = "/.{0,}FROM {1,}/";

    const ER_CONTEUDO_APOS_O_DTO = "/ {0,}WHERE.{1,}/";

    const ER_TUDO_QUE_NAO_FOR_CAMPO = "/SELECT (DISTINCT)?| FROM .{1,}/";

    private $infraQuery = "";

    private $strNomeDto = "";

    private $arrCamposARetornar = array();

    private $arrParametrosInformados = array();

    public function __construct($strInfraQuery)
    {
        $this->infraQuery = $strInfraQuery;
        $this->retirarDaQueryEspacosAdicionaisEQuebrasDeLinha();
        $this->extrairNomeDto();
        $this->extrairCamposARetornar();
    }

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
        $aplicarTrim = function ($campo) {
            return trim($campo);
        };
        $this->arrCamposARetornar = array_map($aplicarTrim, $this->arrCamposARetornar);
    }

    public function gerarDto()
    {
        // TODO Excluir todos os comentários após concluir a versão básica do DtoBuilder de forma a tornar o código mais legível.
        $objDto = null;
        // Criando DTO
        eval('$objDto = new ' . $this->strNomeDto . '();');
        // Verificando necessidade de distinct
        if (strpos($this->infraQuery, "SELECT DISTINCT")) {
            $objDto->setDistinct(self::DEVE_USAR_DISTINCT);
        }
        // Assegurando chamada a campos que devem ser retornados
        foreach ($this->arrCamposARetornar as $strCampo) {
            if ($strCampo == '*') {
                $objDto->retTodos();
            } else {
                eval('$objDto->ret' . $strCampo . '();');
            }
        }
        // Identificando condições no WHERE
        if (strpos($this->infraQuery, " WHERE ")) { // TODO A extração das condições deve ser efetuada no construtor. Aqui só serão deixadas as chamadas ao DTO.
            $arrCondicoes = null;
            preg_match_all(self::ER_CONDICAO, $this->infraQuery, $arrCondicoes);
            foreach ($arrCondicoes[0] as $condicao) {
                $strCampoCondicao = trim(preg_replace(self::ER_CONDICAO_CAMPO, "", $condicao));
                $strValorCondicao = trim(preg_replace(self::ER_CONDICAO_VALOR, "", $condicao));
                if (strpos($strValorCondicao, ":") === 0) {
                    $strValorCondicao = $this->arrParametrosInformados[substr($strValorCondicao, 1)];
                }
                eval('$objDto->set' . $strCampoCondicao . '(' . $strValorCondicao . ');');
            }
        }
        return $objDto;
    }

    public function setParam($strNomeParametro, $valorQualquer)
    {
        if (strpos($strNomeParametro, ":") === 0) {
            $this->arrParametrosInformados[substr($strNomeParametro, 1)] = $valorQualquer;
        } else {
            $this->arrParametrosInformados[$strNomeParametro] = $valorQualquer;
        }
    }
}

