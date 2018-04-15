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
        // Verificando se existe asterisco como campo na consulta
        $existeAsterisco = strpos($sqlSemEspacosAdicionais, "SELECT * FROM") != false;
        if ($existeAsterisco) {
            $dto->retTodos();
        }
        return $dto;
    }
}

