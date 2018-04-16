<?php
namespace InfraQL;

class DtoBuilder
{

    private $infraQuery = "";

    private $nomeDto = "";

    private $camposARetornar = array();

    public function __construct($infraQuery)
    {
        $this->infraQuery = $infraQuery;
        $this->retirarDaQueryEspacosAdicionaisEQuebrasDeLinha();
        $this->extrairNomeDto();
        $this->extrairCamposARetornar();
    }

    private function retirarDaQueryEspacosAdicionaisEQuebrasDeLinha()
    {
        $querySemQuebrasDeLinha = preg_replace("/\r|\n/", "", $this->infraQuery);
        $querySemEspacosAdicionais = preg_replace("/ {1,}/", " ", $querySemQuebrasDeLinha);
        $this->infraQuery = $querySemEspacosAdicionais;
    }

    private function extrairNomeDto()
    {
        $this->nomeDto = preg_replace("/.{0,}FROM {1,}/", " ", $this->infraQuery);
        $this->nomeDto = preg_replace("/ {0,}WHERE.{1,}/", " ", $this->nomeDto);
    }

    private function extrairCamposARetornar()
    {
        $strCamposARetornar = preg_replace("/SELECT (DISTINCT)?| FROM .{1,}/", " ", $this->infraQuery);
        $strCamposARetornar = trim($strCamposARetornar);
        $this->camposARetornar = explode(",", $strCamposARetornar);
        $aplicarTrim = function ($campo) {
            return trim($campo);
        };
        $this->camposARetornar = array_map($aplicarTrim, $this->camposARetornar);
    }

    public function gerarDto()
    {
        // TODO Excluir todos os comentários após concluir a versão básica do DtoBuilder de forma a tornar o código mais legível.
        $dto = null;
        // Criando DTO
        eval('$dto = new ' . $this->nomeDto . '();');
        // Verificando necessidade de distinct
        if (strpos($this->infraQuery, "SELECT DISTINCT")) {
            $dto->setDistinct(true); // TODO Encontrar o significado do parâmetro do 'setDistinct'!!!! ...para ajustar o nome dele!
        }
        // Assegurando chamada a campos que devem ser retornados
        foreach ($this->camposARetornar as $campo) {
            if ($campo == '*') {
                $dto->retTodos();
            } else {
                eval('$dto->ret' . $campo . '();');
            }
        }
        // Identificando condições no WHERE
        if (strpos($this->infraQuery, " WHERE ")) { // TODO A extração das condições deve ser efetuada no construtor. Aqui só serão deixadas as chamadas ao DTO.
            $condicao = trim(preg_replace("/.{0,}WHERE {1,}/", " ", $this->infraQuery));
            $campoCondicao = trim(preg_replace("/( |=){1,}.{0,}/", "", $condicao));
            $valorCondicao = trim(preg_replace("/.{0,}( |=){1,}/", "", $condicao));
            eval('$dto->set' . $campoCondicao . '(' . $valorCondicao . ');');
        }
        return $dto;
    }
}

