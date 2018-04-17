<?php
namespace FakeClass;

abstract class InfraDTOFake
{

    private $retTodosFoiChamado = false;

    public function retTodos()
    {
        $this->retTodosFoiChamado = true;
    }

    public function getRetTodosFoiChamado()
    {
        return $this->retTodosFoiChamado;
    }

    public function adicionarCriterio($varAtributos, $varOperadoresAtributos, $varValoresAtributos, $varOperadoresLogicos = null, $strNome = null)
    {}
}

