<?php
namespace InfraQL;

class DtoBuilder
{
    private $strInfraQuery = "";

    private $strNomeDto = "";

    private $arrCamposARetornar = array();

    private $arrParametrosInformados = array();

    private $arrGrupos = array();

    private $operadoresLogicosGrupos = array();

    private function retirarDaQueryEspacosAdicionaisEQuebrasDeLinha()
    {
        $querySemQuebrasDeLinha = preg_replace(Constantes::ER_QUEBRAS_DE_LINHA, "", $this->strInfraQuery);
        $querySemEspacosAdicionais = preg_replace(Constantes::ER_ESPACOS_DUPLICADOS, " ", $querySemQuebrasDeLinha);
        $this->strInfraQuery = $querySemEspacosAdicionais;
    }

    private function extrairNomeDto()
    {
        $this->strNomeDto = preg_replace(Constantes::ER_CONTEUDO_ANTES_DO_DTO, " ", $this->strInfraQuery);
        $this->strNomeDto = preg_replace(Constantes::ER_CONTEUDO_APOS_O_DTO, " ", $this->strNomeDto);
    }

    private function extrairCamposARetornar()
    {
        $strCamposARetornar = preg_replace(Constantes::ER_TUDO_QUE_NAO_FOR_CAMPO, " ", $this->strInfraQuery);
        $strCamposARetornar = trim($strCamposARetornar);
        if (strlen($strCamposARetornar) > 0) {
            $this->arrCamposARetornar = explode(",", $strCamposARetornar);
            $this->arrCamposARetornar = array_map(function ($campo) {return trim($campo);}, $this->arrCamposARetornar);
        } else {
            $this->arrCamposARetornar = array(Constantes::CARACTER_ASTERISCO);
        }
    }

    private function extrairCondicoesWhere()
    {
        $arrTextoBrutoGrupos = array();
        preg_match_all("/(OR|AND)? ?\('?\b[^ ]{1,}\b'? {0,}(=|<>) {0,}:?'?\b[^ ]{1,}\b'?\)/", $this->strInfraQuery, $arrTextoBrutoGrupos);
        if (sizeof($arrTextoBrutoGrupos[0]) > 0) {
            foreach ($arrTextoBrutoGrupos[0] as $textoBrutoGrupo) {
                $operadorLogicoGrupo = array();
                preg_match_all("/^(OR|AND)/", $textoBrutoGrupo, $operadorLogicoGrupo);
                if (sizeof($operadorLogicoGrupo[0]) > 0) {
                    $this->operadoresLogicosGrupos[] = $operadorLogicoGrupo[0][0];
                }
                $textoBrutoGrupo = preg_replace("/^(OR|AND)/", "", $textoBrutoGrupo);
                $this->arrGrupos[] = (new ConsumoTextoBrutoGrupoCondicao())->consumir($textoBrutoGrupo, $this->arrParametrosInformados);
            }
        } else {
            $this->arrGrupos[] = (new ConsumoTextoBrutoGrupoCondicao())->consumir($this->strInfraQuery, $this->arrParametrosInformados);
        }

    }

    private function clausulaWhereFoiInformada()
    {
        return strpos($this->strInfraQuery, " WHERE ");
    }

    private function distinctFoiInformado()
    {
        return strpos($this->strInfraQuery, "SELECT DISTINCT");
    }

    private function adicionarAoDtoCondicoesDaClausulaWhere($objDto)
    {
        if ($this->clausulaWhereFoiInformada()) {
            $this->extrairCondicoesWhere();
            foreach ($this->arrGrupos as $indice => $grupo) {
                if (sizeof($grupo->getArrOperadoresLogicos()) > 0) {
                    $objDto->adicionarCriterio($grupo->getArrCamposCondicao(), $grupo->getArrOperadoresComparacao(), $grupo->getArrValoresCondicao(), $grupo->getArrOperadoresLogicos(), "CRITERIO_{$indice}");
                } else {
                    $objDto->adicionarCriterio($grupo->getArrCamposCondicao(), $grupo->getArrOperadoresComparacao(), $grupo->getArrValoresCondicao(), null, "CRITERIO_{$indice}");
                }
            }
            $objDto->agruparCriterios(array_keys($this->arrGrupos), null);
        }
    }

    public function __construct($strInfraQuery)
    {
        $this->strInfraQuery = $strInfraQuery;
        $this->retirarDaQueryEspacosAdicionaisEQuebrasDeLinha();
        $this->extrairNomeDto();
        $this->extrairCamposARetornar();
    }

    public function setParam($strNomeParametro, $varValorQualquer)
    {
        if (strpos($strNomeParametro, Constantes::CARACTER_DOIS_PONTOS) === 0) {
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
            $objDto->setDistinct(Constantes::PARAMETRO_DEVE_USAR_DISTINCT);
        }
        foreach ($this->arrCamposARetornar as $strCampo) {
            if ($strCampo == Constantes::CARACTER_ASTERISCO) {
                $objDto->retTodos();
            } else {
                eval('$objDto->ret' . $strCampo . '();');
            }
        }
        $this->adicionarAoDtoCondicoesDaClausulaWhere($objDto);
        return $objDto;
    }
}
