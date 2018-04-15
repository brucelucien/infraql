<?php
namespace FakeClass;

class PessoaDTO
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
}

