<?php
namespace InfraQL;

class InfraQlLexerTest extends \Codeception\Test\Unit
{
    private const EXEMPLO_INPUT = "SELECT StrNome, NumIdade FROM PessoaDTO WHERE NumIdade >= 18 AND (StrNome = 'Donald Knuth') ORDER BY StrNome, NumIdade";

    protected $objInfraQlLexer;

    protected function _before()
    {
        $this->objInfraQlLexer = new InfraQlLexer(self::EXEMPLO_INPUT);
    }

    protected function _after()
    {
        $this->objInfraQlLexer = null;
    }

    public function testDeveIdentificarOSelectComoToken()
    {
        $token = $this->objInfraQlLexer->getObjNextToken();
        $this->assertEquals(InfraQlLexer::SELECT, $token->getNumType());
        $this->assertEquals("SELECT", $token->getStrText());
    }

    public function testDeveIdentificarOsCamposStrNome()
    {
        $this->objInfraQlLexer->getObjNextToken(); // Pular o SELECT...
        $token = $this->objInfraQlLexer->getObjNextToken();
        $this->assertEquals(InfraQlLexer::FIELD_NAME, $token->getNumType());
        $this->assertEquals("StrNome", $token->getStrText());
    }

    public function testDeveIdentificarAVirgulaEntreOsCampos()
    {
        $this->objInfraQlLexer->getObjNextToken(); // Pular o SELECT...
        $this->objInfraQlLexer->getObjNextToken(); // Pular o campo StrNome...
        $token = $this->objInfraQlLexer->getObjNextToken();
        $this->assertEquals(InfraQlLexer::COMMA, $token->getNumType());
        $this->assertEquals(",", $token->getStrText());
    }

    public function testDeveIdentificarOsCamposNumIdade()
    {
        $this->objInfraQlLexer->getObjNextToken(); // Pular o SELECT...
        $this->objInfraQlLexer->getObjNextToken(); // Pular o campo StrNome...
        $this->objInfraQlLexer->getObjNextToken(); // Pular a vírgula...
        $token = $this->objInfraQlLexer->getObjNextToken();
        $this->assertEquals(InfraQlLexer::FIELD_NAME, $token->getNumType());
        $this->assertEquals("NumIdade", $token->getStrText());
    }

    public function testDeveIdentificarOFrom()
    {
        $this->objInfraQlLexer->getObjNextToken(); // Pular o SELECT...
        $this->objInfraQlLexer->getObjNextToken(); // Pular o campo StrNome...
        $this->objInfraQlLexer->getObjNextToken(); // Pular a vírgula...
        $this->objInfraQlLexer->getObjNextToken(); // Pular o campo NumIdade...
        $token = $this->objInfraQlLexer->getObjNextToken();
        $this->assertEquals(InfraQlLexer::FROM, $token->getNumType());
        $this->assertEquals("FROM", $token->getStrText());
    }

    public function testDeveIdentificarONomeDoDto()
    {
        $this->objInfraQlLexer->getObjNextToken(); // Pular o SELECT...
        $this->objInfraQlLexer->getObjNextToken(); // Pular o campo StrNome...
        $this->objInfraQlLexer->getObjNextToken(); // Pular a vírgula...
        $this->objInfraQlLexer->getObjNextToken(); // Pular o campo NumIdade...
        $this->objInfraQlLexer->getObjNextToken(); // Pular o FROM...
        $token = $this->objInfraQlLexer->getObjNextToken();
        $this->assertEquals(InfraQlLexer::DTO_NAME, $token->getNumType());
        $this->assertEquals("PessoaDTO", $token->getStrText());
    }

    public function testDeveIdentificarOWhere()
    {
        $this->objInfraQlLexer->getObjNextToken(); // Pular o SELECT...
        $this->objInfraQlLexer->getObjNextToken(); // Pular o campo StrNome...
        $this->objInfraQlLexer->getObjNextToken(); // Pular a vírgula...
        $this->objInfraQlLexer->getObjNextToken(); // Pular o campo NumIdade...
        $this->objInfraQlLexer->getObjNextToken(); // Pular o FROM...
        $this->objInfraQlLexer->getObjNextToken(); // Pular o nome do DTO...
        $token = $this->objInfraQlLexer->getObjNextToken();
        $this->assertEquals(InfraQlLexer::WHERE, $token->getNumType());
        $this->assertEquals("WHERE", $token->getStrText());
    }

    public function testDeveIdentificarNumIdadeNaClausulaWhere()
    {
        $this->objInfraQlLexer->getObjNextToken(); // Pular o SELECT...
        $this->objInfraQlLexer->getObjNextToken(); // Pular o campo StrNome...
        $this->objInfraQlLexer->getObjNextToken(); // Pular a vírgula...
        $this->objInfraQlLexer->getObjNextToken(); // Pular o campo NumIdade...
        $this->objInfraQlLexer->getObjNextToken(); // Pular o FROM...
        $this->objInfraQlLexer->getObjNextToken(); // Pular o nome do DTO...
        $this->objInfraQlLexer->getObjNextToken(); // Pular o WHERE...
        $token = $this->objInfraQlLexer->getObjNextToken();
        $this->assertEquals(InfraQlLexer::FIELD_NAME, $token->getNumType());
        $this->assertEquals("NumIdade", $token->getStrText());
    }

    public function testDeveIdentificarOMaiorOuIgualQueNaPrimeiraCondicao()
    {
        $this->objInfraQlLexer->getObjNextToken(); // Pular o SELECT...
        $this->objInfraQlLexer->getObjNextToken(); // Pular o campo StrNome...
        $this->objInfraQlLexer->getObjNextToken(); // Pular a vírgula...
        $this->objInfraQlLexer->getObjNextToken(); // Pular o campo NumIdade...
        $this->objInfraQlLexer->getObjNextToken(); // Pular o FROM...
        $this->objInfraQlLexer->getObjNextToken(); // Pular o nome do DTO...
        $this->objInfraQlLexer->getObjNextToken(); // Pular o WHERE...
        $this->objInfraQlLexer->getObjNextToken(); // Pular o campo NumIdade...
        $token = $this->objInfraQlLexer->getObjNextToken();
        $this->assertEquals(InfraQlLexer::GREATER_THAN_OR_EQUAL_TO, $token->getNumType());
        $this->assertEquals(">=", $token->getStrText());
    }

    public function testDeveIdentificarONumeroNaPrimeiraCondicao()
    {
        $this->objInfraQlLexer->getObjNextToken(); // Pular o SELECT...
        $this->objInfraQlLexer->getObjNextToken(); // Pular o campo StrNome...
        $this->objInfraQlLexer->getObjNextToken(); // Pular a vírgula...
        $this->objInfraQlLexer->getObjNextToken(); // Pular o campo NumIdade...
        $this->objInfraQlLexer->getObjNextToken(); // Pular o FROM...
        $this->objInfraQlLexer->getObjNextToken(); // Pular o nome do DTO...
        $this->objInfraQlLexer->getObjNextToken(); // Pular o WHERE...
        $this->objInfraQlLexer->getObjNextToken(); // Pular o campo NumIdade...
        $this->objInfraQlLexer->getObjNextToken(); // Pular o operador de comparação...
        $token = $this->objInfraQlLexer->getObjNextToken();
        $this->assertEquals(InfraQlLexer::NUMBER_INTEGER, $token->getNumType());
        $this->assertEquals("18", $token->getStrText());
    }

    public function testMetodoParaTestarLetrasDeveFuncionarCorretamente()
    {
        $objInfraQlLexer = new InfraQlLexer("A1Z9aH5z");
        $this->assertTrue($objInfraQlLexer->isLetter());
        $objInfraQlLexer->consume();
        $this->assertFalse($objInfraQlLexer->isLetter());
        $objInfraQlLexer->consume();
        $this->assertTrue($objInfraQlLexer->isLetter());
        $objInfraQlLexer->consume();
        $this->assertFalse($objInfraQlLexer->isLetter());
        $objInfraQlLexer->consume();
        $this->assertTrue($objInfraQlLexer->isLetter());
        $objInfraQlLexer->consume();
        $this->assertTrue($objInfraQlLexer->isLetter());
        $objInfraQlLexer->consume();
        $this->assertFalse($objInfraQlLexer->isLetter());
        $objInfraQlLexer->consume();
        $this->assertTrue($objInfraQlLexer->isLetter());
    }

    public function testMetodoParaTestarNumerosDeveFuncionarCorretamente()
    {
        $objInfraQlLexer = new InfraQlLexer("A1Z9");
        $this->assertFalse($objInfraQlLexer->isNumber());
        $objInfraQlLexer->consume();
        $this->assertTrue($objInfraQlLexer->isNumber());
        $objInfraQlLexer->consume();
        $this->assertFalse($objInfraQlLexer->isNumber());
        $objInfraQlLexer->consume();
        $this->assertTrue($objInfraQlLexer->isNumber());
    }

    public function testOUltimoTokenLidoDeveIniciarComoVazio()
    {
        $this->assertEquals(InfraQlLexer::VOID, $this->objInfraQlLexer->getObjPreviousToken()->getNumType());
        $this->assertEquals("", $this->objInfraQlLexer->getObjPreviousToken()->getStrText());
    }

    public function testAposPularOCampoStrNomeOCampoDeveSerRetornadoComoOUltimoTokenLido()
    {
        $this->objInfraQlLexer->getObjNextToken(); // Pular o SELECT...
        $this->objInfraQlLexer->getObjNextToken(); // Pular o campo StrNome...
        $this->assertEquals(InfraQlLexer::FIELD_NAME, $this->objInfraQlLexer->getObjPreviousToken()->getNumType());
        $this->assertEquals("StrNome", $this->objInfraQlLexer->getObjPreviousToken()->getStrText());
    }

    public function testQuandoChegarAoFinalDoTextoDeveRetornarUmTokenDeEof()
    {
        $objInfraQlLexer = new InfraQlLexer("SELECT");
        $objInfraQlLexer->getObjNextToken(); // Pular SELECT...
        $token = $objInfraQlLexer->getObjNextToken();
        $this->assertEquals(InfraQlLexer::EOF_TYPE, $token->getNumType());
        $this->assertEquals("", $token->getStrText());
    }
}
