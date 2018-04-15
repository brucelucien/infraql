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
        
        $nomeCampo = preg_replace("/SELECT | FROM .{1,}/", " ", $sqlSemEspacosAdicionais);
        $nomeCampo = trim($nomeCampo);
        
        if ($nomeCampo == '*') {
            $dto->retTodos();
        } else {
            eval('$dto->ret' . $nomeCampo . '();');
        }
        
        return $dto;
    }
}

