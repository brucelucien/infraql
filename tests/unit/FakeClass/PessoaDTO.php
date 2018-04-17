<?php
namespace FakeClass;

class PessoaDTO extends InfraDTOFake
{

    private $retStrNomeFoiChamado = false;

    private $retStrSinAtivoFoiChamado = false;

    private $retStrSexoFoiChamado = false;

    private $setDistinctFoiChamado = false;

    private $strSinAtivo = '';

    private $numIdade = 0;

    private $strCpf = '';

    private $strCorPreferida = '';

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

    public function setDistinct($bolDeveUsarDistinctNaQuery)
    {
        $this->setDistinctFoiChamado = true;
    }

    public function getSetDistinctFoiChamado()
    {
        return $this->setDistinctFoiChamado;
    }

    public function getStrSinAtivo()
    {
        return $this->strSinAtivo;
    }

    public function setStrSinAtivo($strSinAtivo)
    {
        $this->strSinAtivo = $strSinAtivo;
    }

    public function getNumIdade()
    {
        return $this->numIdade;
    }

    public function setNumIdade($numIdade)
    {
        $this->numIdade = $numIdade;
    }

    public function getStrCpf()
    {
        return $this->strCpf;
    }

    public function setStrCpf($strCpf)
    {
        $this->strCpf = $strCpf;
    }

    public function getStrCorPreferida()
    {
        return $this->strCorPreferida;
    }

    public function setStrCorPreferida($strCorPreferida)
    {
        $this->strCorPreferida = $strCorPreferida;
    }
}

