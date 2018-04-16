<?php
namespace InfraQL;

class DtoBuilder
{

    public function gerarDto($sql)
    {
        $dto = null;
        $sqlSemQuebrasDeLinha = preg_replace("/\r|\n/", "", $sql);
        $sqlSemEspacosAdicionais = preg_replace("/ {1,}/", " ", $sqlSemQuebrasDeLinha);
        // Extraindo nome do DTO
        $nomeDto = preg_replace("/.{0,}FROM {0,}/", " ", $sqlSemEspacosAdicionais);
        // Criando DTO
        eval('$dto = new ' . $nomeDto . '();');
        // Verificando necessidade de distinct
        if (strpos($sqlSemEspacosAdicionais, "SELECT DISTINCT")) {
            $dto->setDistinct(true);
        }
        // Identificando campos a retornar
        $strCamposARetornar = preg_replace("/SELECT (DISTINCT)?| FROM .{1,}/", " ", $sqlSemEspacosAdicionais);
        $strCamposARetornar = trim($strCamposARetornar);
        $camposARetornar = explode(",", $strCamposARetornar);
        $aplicarTrim = function ($campo) {
            return trim($campo);
        };
        $camposARetornar = array_map($aplicarTrim, $camposARetornar);
        foreach ($camposARetornar as $campo) {
            if ($campo == '*') {
                $dto->retTodos();
            } else {
                eval('$dto->ret' . $campo . '();');
            }
        }
        return $dto;
    }
}

