<?php
namespace InfraQL;

class InfraQlLexerOperadoresComparacaoTest extends \Codeception\Test\Unit
{

    protected function _before()
    {}

    protected function _after()
    {}

    private function testarOperador(string $operadorATestar, int $tokenTypeEsperado): void
    {
        $strInput = "
            FROM
                UsuarioDTO
            WHERE
                IdUsuario {$operadorATestar} 42
        ";
        $objInfraQlLexer = new InfraQlLexer($strInput);
        $objInfraQlLexer->getObjNextToken(); // FROM
        $objInfraQlLexer->getObjNextToken(); // UsuarioDTO
        $objInfraQlLexer->getObjNextToken(); // WHERE
        $objInfraQlLexer->getObjNextToken(); // IdUsuario
        $objToken = $objInfraQlLexer->getObjNextToken(); // Operador
        $this->assertEquals($tokenTypeEsperado, $objToken->getNumType());
        $this->assertEquals($operadorATestar, $objToken->getStrText());
    }

    public function testDeveIdentificarTodosOsOperadoresDeComparacaoEmSql()
    {
        $this->testarOperador("=", InfraQlTokenType::EQUAL);
        $this->testarOperador(">", InfraQlTokenType::GREATER_THAN);
        $this->testarOperador("<", InfraQlTokenType::LESS_THAN);
        $this->testarOperador(">=", InfraQlTokenType::GREATER_THAN_OR_EQUAL_TO);
        $this->testarOperador("<=", InfraQlTokenType::LESS_THAN_OR_EQUAL_TO);
        $this->testarOperador("<>", InfraQlTokenType::NOT_EQUAL_TO);
    }
}
