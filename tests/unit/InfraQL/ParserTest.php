<?php
namespace InfraQL;

class ParserTest extends \Codeception\Test\Unit
{

    protected function _before()
    {}

    protected function _after()
    {}

    public function testDeveArmazenarOLexerPassadoNoConstrutor()
    {
        $objLexer = new InfraQlLexer("SELECT");
        $objParser = new class($objLexer) extends Parser {};
        $this->assertEquals($objLexer->getStrInput(), $objParser->getObjInput()
            ->getStrInput());
    }

    public function testDeveAvancarParaOPrimeiroTokenAoSerInstanciado()
    {
        $objLexer = new InfraQlLexer("SELECT");
        $objParser = new class($objLexer) extends Parser {};
        $this->assertEquals(InfraQlTokenType::SELECT, $objParser->getObjLookahead()
            ->getNumType());
    }

    public function testDeveAvancarParaOProximoTokenAoCasarComTokenCorreto()
    {
        $objLexer = new InfraQlLexer("SELECT IdAnalise FROM AnaliseDTO");
        $objParser = new class($objLexer) extends Parser {};
        $objParser->match(InfraQlTokenType::SELECT);
        $this->assertEquals(InfraQlTokenType::FIELD_NAME, $objParser->getObjLookahead()
            ->getNumType());
        $this->assertEquals("IdAnalise", $objParser->getObjLookahead()
            ->getStrText());
    }

    public function testDeveLancarExcecaoQuandoTokenInformadoNaoCasar()
    {
        $bolExcecaoLancada = false;
        $objLexer = new InfraQlLexer("SELECT IdAnalise FROM AnaliseDTO");
        $objParser = new class($objLexer) extends Parser {};
        try {
            $objParser->match(InfraQlTokenType::FROM);
        } catch (\Exception $e) {
            $bolExcecaoLancada = true;
            $msgEsperada = sprintf("Era esperado '%s' mas foi encontrado '%s'.", InfraQlTokenName::getStrName(InfraQlTokenType::FROM), InfraQlTokenName::getStrName(InfraQlTokenType::SELECT));
            $this->assertEquals($msgEsperada, $e->getMessage());
        }
        if (! $bolExcecaoLancada) {
            $this->fail("Uma excecao deve ser lancada.");
        }
    }
}
