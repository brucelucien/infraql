<?php
namespace FakeClass;

abstract class InfraDTOFake
{

    private $retTodosFoiChamado = false;

    private $varAtributos = null;

    private $varOperadoresAtributos = null;

    private $varValoresAtributos = null;

    private $varOperadoresLogicos = null;

    private $strNome = null;

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

    public function getStrNome()
    {
        return $this->strNome;
    }

    public function adicionarCriterio($varAtributos, $varOperadoresAtributos, $varValoresAtributos, $varOperadoresLogicos = null, array $strNome = null)
    {
        $this->varAtributos = $varAtributos;
        $this->varOperadoresAtributos = $varOperadoresAtributos;
        $this->varValoresAtributos = $varValoresAtributos;
        $this->varOperadoresLogicos = $varOperadoresLogicos;
        $this->strNome = $strNome;
    }
}
