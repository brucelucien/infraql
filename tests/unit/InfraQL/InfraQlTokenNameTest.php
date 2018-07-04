<?php
namespace InfraQL;

class InfraQlTokenNameTest extends \Codeception\Test\Unit
{

    private $objLexer;

    protected function _before()
    {
        $this->objLexer = new InfraQlLexer("");
    }

    protected function _after()
    {
        $this->objLexer = null;
    }

    public function testDeveRetornarOsNomesCorretosDosTokens()
    {
        $this->assertEquals(InfraQlTokenName::NAMES[InfraQlTokenType::SELECT], InfraQlTokenName::getStrName(InfraQlTokenType::SELECT));
        $this->assertEquals(InfraQlTokenName::NAMES[InfraQlTokenType::FROM], InfraQlTokenName::getStrName(InfraQlTokenType::FROM));
        $this->assertEquals(InfraQlTokenName::NAMES[InfraQlTokenType::ORDER_BY], InfraQlTokenName::getStrName(InfraQlTokenType::ORDER_BY));
    }
}
