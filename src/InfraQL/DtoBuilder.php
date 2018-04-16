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
        $nomeDto = preg_replace("/.{0,}FROM {1,}/", " ", $sqlSemEspacosAdicionais);
        $nomeDto = preg_replace("/ {0,}WHERE.{1,}/", " ", $nomeDto);
        // Criando DTO
        eval('$dto = new ' . $nomeDto . '();');
        // Verificando necessidade de distinct
        if (strpos($sqlSemEspacosAdicionais, "SELECT DISTINCT")) {
            $dto->setDistinct(true); // TODO Encontrar o significado do parâmetro do 'setDistinct'!!!! ...para ajustar o nome dele!
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
        // Identificando condições no WHERE
        if (strpos($sqlSemEspacosAdicionais, " WHERE ")) {
            $condicao = trim(preg_replace("/.{0,}WHERE {1,}/", " ", $sqlSemEspacosAdicionais));
            $campoCondicao = trim(preg_replace("/( |=){1,}.{0,}/", "", $condicao));
            $valorCondicao = trim(preg_replace("/.{0,}( |=){1,}/", "", $condicao));
            eval('$dto->set' . $campoCondicao . '(' . $valorCondicao . ');');
        }
        return $dto;
    }
}

