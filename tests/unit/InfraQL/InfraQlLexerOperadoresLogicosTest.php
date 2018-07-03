<?php
namespace InfraQL;

class InfraQlLexerOperadoresLogicosTest extends \Codeception\Test\Unit
{

    protected function _before()
    {}

    protected function _after()
    {}

    public function testDeveIdentificarOr()
    {
        $strInput = <<<INFRAQL
            FROM
                PessoaDTO
            WHERE
                IdPessoa = 12
                OR IdPessoa = 9
                OR IdPessoa = 1985
INFRAQL;
        $objInfraQlLexer = new InfraQlLexer($strInput);
        $objInfraQlLexer->getObjNextToken(); // FROM
        $objInfraQlLexer->getObjNextToken(); // PessoaDTO
        $objInfraQlLexer->getObjNextToken(); // WHERE
        $objInfraQlLexer->getObjNextToken(); // IdPessoa
        $objInfraQlLexer->getObjNextToken(); // =
        $objInfraQlLexer->getObjNextToken(); // 12
        $objToken = $objInfraQlLexer->getObjNextToken(); // OR
        $this->assertEquals(InfraQlTokenType::LOGICAL_OPERATOR_OR, $objToken->getNumType());
        $this->assertEquals("OR", $objToken->getStrText());
        $objInfraQlLexer->getObjNextToken(); // IdPessoa
        $objInfraQlLexer->getObjNextToken(); // =
        $objInfraQlLexer->getObjNextToken(); // 9
        $objToken = $objInfraQlLexer->getObjNextToken(); // OR
        $this->assertEquals(InfraQlTokenType::LOGICAL_OPERATOR_OR, $objToken->getNumType());
        $this->assertEquals("OR", $objToken->getStrText());
    }

    public function testVirgulaSemCampoAposSignificaErro()
    {
        $strInput = <<<INFRAQL
            FROM
                EstadoDTO
            WHERE
                Nome = 'Rio Grande do Sul'
                AND (
                    Sigla = 'RS'
                    OR Sigla = 'rs'
                )
INFRAQL;
        $objInfraQlLexer = new InfraQlLexer($strInput);
        $objInfraQlLexer->getObjNextToken(); // FROM
        $objInfraQlLexer->getObjNextToken(); // EstadoDTO
        $objInfraQlLexer->getObjNextToken(); // WHERE
        $objInfraQlLexer->getObjNextToken(); // Nome
        $objInfraQlLexer->getObjNextToken(); // =
        $objInfraQlLexer->getObjNextToken(); // 'Rio Grande do Sul'
        $objToken = $objInfraQlLexer->getObjNextToken(); // AND
        $this->assertEquals(InfraQlTokenType::LOGICAL_OPERATOR_AND, $objToken->getNumType());
        $this->assertEquals("AND", $objToken->getStrText());
        $objInfraQlLexer->getObjNextToken(); // (
        $objInfraQlLexer->getObjNextToken(); // Sigla
        $objInfraQlLexer->getObjNextToken(); // =
        $objInfraQlLexer->getObjNextToken(); // 'RS'
        $objToken = $objInfraQlLexer->getObjNextToken(); // OR
        $this->assertEquals(InfraQlTokenType::LOGICAL_OPERATOR_OR, $objToken->getNumType());
        $this->assertEquals("OR", $objToken->getStrText());
    }
}
