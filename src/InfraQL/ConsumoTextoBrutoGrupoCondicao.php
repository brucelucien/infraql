<?php
namespace InfraQL;

class ConsumoTextoBrutoGrupoCondicao
{

    public function consumir($strTextoBruto, $arrParametrosInformados)
    {
        $novoGrupo = new GrupoCondicao();
        $arrCondicoes = null;
        preg_match_all(Constantes::ER_CONDICAO, $strTextoBruto, $arrCondicoes);
        foreach ($arrCondicoes[0] as $condicao) {
            $strCampoCondicao = trim(preg_replace(Constantes::ER_CONDICAO_CAMPO, "", $condicao));
            $strValorCondicao = trim(preg_replace(Constantes::ER_CONDICAO_VALOR, "", $condicao));
            $novoGrupo->addArrCamposCondicao(substr($strCampoCondicao, 3));
            if (strpos($strValorCondicao, Constantes::CARACTER_DOIS_PONTOS) === 0) {
                $strValorCondicao = $arrParametrosInformados[substr($strValorCondicao, 1)];
            }
            $novoGrupo->addArrValoresCondicao(preg_replace(Constantes::ER_EXCLUIR_ASPAS, "", $strValorCondicao));
        }
        $arrOperadoresLogicos = array();
        preg_match_all("/" . Constantes::ER_OPERADORES_LOGICOS_ESPERADOS . "/", $strTextoBruto, $arrOperadoresLogicos);
        $novoGrupo->setArrOperadoresLogicos($arrOperadoresLogicos[0]);
        $arrOperadoresComparacao = array();
        preg_match_all("/" . Constantes::ER_OPERADORES_COMPARACAO_ESPERADOS . "/", $strTextoBruto, $arrOperadoresComparacao);
        $novoGrupo->setArrOperadoresComparacao($arrOperadoresComparacao[0]);
        return $novoGrupo;
    }
}
