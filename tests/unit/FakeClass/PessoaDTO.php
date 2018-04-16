<?php
namespace FakeClass;

class PessoaDTO
{

    private $retTodosFoiChamado = false;

    private $retStrNomeFoiChamado = false;

    private $retStrSinAtivoFoiChamado = false;

    private $retStrSexoFoiChamado = false;

    private $setDistinctFoiChamado = false;

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

    public function setDistinct($parametro) // TODO Encontrar o significado do parâmetro do 'setDistinct'!!!! ...para ajustar o nome dele aqui!
    {
        $this->setDistinctFoiChamado = true;
    }

    public function getSetDistinctFoiChamado()
    {
        return $this->setDistinctFoiChamado;
    }
}

