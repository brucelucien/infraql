<?php
namespace InfraQL;

class GrupoCondicao
{

    private $arrCamposCondicao = array();

    private $arrOperadoresComparacao = array();

    private $arrValoresCondicao = array();

    private $arrOperadoresLogicos = array();

    public function getArrCamposCondicao()
    {
        return $this->arrCamposCondicao;
    }

    public function setArrCamposCondicao($arrCamposCondicao)
    {
        $this->arrCamposCondicao = $arrCamposCondicao;
    }

    public function getArrOperadoresComparacao()
    {
        return $this->arrOperadoresComparacao;
    }

    public function setArrOperadoresComparacao($arrOperadoresComparacao)
    {
        $this->arrOperadoresComparacao = $arrOperadoresComparacao;
    }

    public function getArrValoresCondicao()
    {
        return $this->arrValoresCondicao;
    }

    public function setArrValoresCondicao($arrValoresCondicao)
    {
        $this->arrValoresCondicao = $arrValoresCondicao;
    }

    public function getArrOperadoresLogicos()
    {
        return $this->arrOperadoresLogicos;
    }

    public function setArrOperadoresLogicos($arrOperadoresLogicos)
    {
        $this->arrOperadoresLogicos = $arrOperadoresLogicos;
    }

    public function addArrCamposCondicao($strCampoCondicao)
    {
        $this->arrCamposCondicao[] = $strCampoCondicao;
    }

    public function addArrOperadoresComparacao($strOperadorComparacao)
    {
        $this->arrOperadoresComparacao[] = $strOperadorComparacao;
    }

    public function addArrValoresCondicao($strValorCondicao)
    {
        $this->arrValoresCondicao[] = $strValorCondicao;
    }

    public function addArrOperadoresLogicos($strOperadorLogico)
    {
        $this->arrOperadoresLogicos[] = $strOperadorLogico;
    }
}
