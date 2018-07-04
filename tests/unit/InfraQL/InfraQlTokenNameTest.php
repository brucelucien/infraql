<?php
namespace InfraQL;

class InfraQlTokenNameTest extends \Codeception\Test\Unit
{

    protected function _before()
    {}

    protected function _after()
    {}

    public function testDeveRetornarOsNomesCorretosDosTokens()
    {
        foreach (InfraQlTokenName::NAMES as $numIndice => $strName) {
            $this->assertEquals($strName, InfraQlTokenName::getStrName($numIndice));
        }
    }

    public function testTokenNaoEncontradoDeveRetornarErro()
    {
        $intTokenTypeInvalido = 7019824712;
        $bolExcecaoLancada = false;
        try {
            InfraQlTokenName::getStrName($intTokenTypeInvalido);
        } catch (\Exception $e) {
            $bolExcecaoLancada = true;
            $strMensagemErro = sprintf("Nao ha nome definido para o tipo de token '%s'.", $intTokenTypeInvalido);
            $this->assertEquals($strMensagemErro, $e->getMessage());
        }
        if (! $bolExcecaoLancada) {
            $this->fail("Uma excecao deve ser lancada.");
        }
    }
}
