<?php
namespace FakeClass;

abstract class InfraDTOFake
{

    private $retTodosFoiChamado = false;

    private $varAtributos = null;

    private $varOperadoresAtributos = null;

    private $varValoresAtributos = null;

    private $varOperadoresLogicos = null;

    private $arrNomesCriterios = array();

    private $arrCriteriosGrupos = array();

    private $varOperadoresLogicosGrupos = array();

    private $arrNomeGrupos = "";

    public function retTodos()
    {
        $this->retTodosFoiChamado = true;
    }

    public function getRetTodosFoiChamado()
    {
        return $this->retTodosFoiChamado;
    }

    public function getVarAtributos()
    {
        return $this->varAtributos;
    }

    public function getVarOperadoresAtributos()
    {
        return $this->varOperadoresAtributos;
    }

    public function getVarValoresAtributos()
    {
        return $this->varValoresAtributos;
    }

    public function getVarOperadoresLogicos()
    {
        return $this->varOperadoresLogicos;
    }

    public function getArrNomesCriterios()
    {
        return $this->arrNomesCriterios;
    }

    public function adicionarCriterio($varAtributos, $varOperadoresAtributos, $varValoresAtributos, $varOperadoresLogicos = null, $strNomeCriterio = null)
    {
        $this->varAtributos = $varAtributos;
        $this->varOperadoresAtributos = $varOperadoresAtributos;
        $this->varValoresAtributos = $varValoresAtributos;
        $this->varOperadoresLogicos = $varOperadoresLogicos;
        if (!is_null($strNomeCriterio)) {
            $this->arrNomesCriterios[] = $strNomeCriterio;
        }
    }

    public function agruparCriterios($arrCriterios, $varOperadoresLogicos, $strNome = null)
    {
        $this->getArrCriteriosGrupos[] = $arrCriterios;
        $this->getVarOperadoresLogicosGrupos[] = $varOperadoresLogicos;
        if (!is_null($strNome)) {
            $this->arrNomeGrupos[] = $strNome;
        }
    }

    public function getArrCriteriosGrupos()
    {
        return $this->arrCriteriosGrupos;
    }

    public function getArrNomeGrupos()
    {
        return $this->arrNomeGrupos;
    }

    public function getVarOperadoresLogicosGrupos()
    {
        return $this->varOperadoresLogicosGrupos;
    }
}
