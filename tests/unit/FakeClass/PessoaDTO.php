<?php
namespace FakeClass;

class PessoaDTO
{

    private $retTodosFoiChamado = false;

    private $retStrNomeFoiChamado = false;

    private $retStrSinAtivoFoiChamado = false;

    private $retStrSexoFoiChamado = false;

    public function retTodos()
    {
        $this->retTodosFoiChamado = true;
    }

    public function getRetTodosFoiChamado()
    {
        return $this->retTodosFoiChamado;
    }

    public function retStrNome()
    {
        $this->retStrNomeFoiChamado = true;
    }

    public function getRetStrNomeFoiChamado()
    {
        return $this->retStrNomeFoiChamado;
    }

    public function retStrSinAtivo()
    {
        $this->retStrSinAtivoFoiChamado = true;
    }

    public function getRetStrSinAtivoFoiChamado()
    {
        return $this->retStrSinAtivoFoiChamado;
    }

    public function retStrSexo()
    {
        $this->retStrSexoFoiChamado = true;
    }

    public function getRetStrSexoFoiChamado()
    {
        return $this->retStrSexoFoiChamado;
    }
}

