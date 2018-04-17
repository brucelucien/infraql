<?php
namespace FakeClass;

class LocalInstalacaoEprocDTO extends InfraDTOFake
{

    private $strSigUf;

    private $strTipoContexto;

    private $strTipoInstancia;

    private $strTipoAmbiente;

    public function getStrSigUf()
    {
        return $this->strSigUf;
    }

    public function getStrTipoContexto()
    {
        return $this->strTipoContexto;
    }

    public function getStrTipoInstancia()
    {
        return $this->strTipoInstancia;
    }

    public function getStrTipoAmbiente()
    {
        return $this->strTipoAmbiente;
    }

    public function setStrSigUf($strSigUf)
    {
        $this->strSigUf = $strSigUf;
    }

    public function setStrTipoContexto($strTipoContexto)
    {
        $this->strTipoContexto = $strTipoContexto;
    }

    public function setStrTipoInstancia($strTipoInstancia)
    {
        $this->strTipoInstancia = $strTipoInstancia;
    }

    public function setStrTipoAmbiente($strTipoAmbiente)
    {
        $this->strTipoAmbiente = $strTipoAmbiente;
    }
}

