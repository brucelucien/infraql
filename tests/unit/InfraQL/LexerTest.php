<?php
namespace InfraQL;

class LexerTest extends \Codeception\Test\Unit
{
    private const EXEMPLO_INPUT = "SELECT StrNome, NumIdade FROM PessoaDTO WHERE StrNome = 'Donald Knuth'";

    protected $objLexer;

    private function criarInstanciaLexerComInputPreenchido()
    {
        return new class(self::EXEMPLO_INPUT) extends Lexer
        {
            public function getObjNextToken(): Token
            {
                return null;
            }

            public function getStrTokenName(int $numTokenType): string
            {
                return "";
            }
        };
    }

    private function criarInstanciaLexerComInputVazio()
    {
        return new class("") extends Lexer
        {
            public function getObjNextToken(): Token
            {
                return null;
            }

            public function getStrTokenName(int $numTokenType): string
            {
                return "";
            }
        };
    }

    protected function _before()
    {
        $this->objLexer = $this->criarInstanciaLexerComInputPreenchido();
    }

    protected function _after()
    {
        $this->objLexer = null;
    }

    public function testInputDeveSerMapeado()
    {
        $this->assertEquals(self::EXEMPLO_INPUT, $this->objLexer->getStrInput());
    }

    public function testDeveSetarOCaractereAFrenteAposInstanciado()
    {
        $this->assertEquals("S", $this->objLexer->getStrCharacter());
    }

    public function testSeInputVazioCaractereDeveSerVazio()
    {
        $this->assertEquals("", $this->criarInstanciaLexerComInputVazio()->getStrCharacter());
    }

    public function testAoInstanciarPosicaoAtualDeveSerZero()
    {
        $this->assertEquals(0, $this->objLexer->getNumPosition());
    }

    public function testAoConsumirDeveIncrementarPosicaoAtual()
    {
        $this->objLexer->consume();
        $this->objLexer->consume();
        $this->assertEquals(2, $this->objLexer->getNumPosition());
    }

    public function testDeveTerUmCaractereParaIndicarOFimDoInput()
    {
        $this->assertEquals("\0", Lexer::EOF);
    }

    public function testDeveTerUmCaractereParaIndicarOFimDoTipoDoInput()
    {
        $this->assertEquals(1, Lexer::EOF_TYPE);
    }

    public function testDeveMudarDeCaractereCorretamenteAposConsumo()
    {
        $this->objLexer->consume();
        $this->objLexer->consume();
        $this->objLexer->consume();
        $this->objLexer->consume();
        $this->assertEquals("C", $this->objLexer->getStrCharacter());
    }

    public function testCaractereDeveSerEofSeConsumirTodaOInput()
    {
        for ($cont = 1; $cont <= strlen($this->objLexer->getStrInput()); $cont++) {
            $this->objLexer->consume();
        }
        $this->assertEquals(Lexer::EOF, $this->objLexer->getStrCharacter());
    }

    public function testSeOCaractereNaoForOEsperadoNoMatchEntaoDeveRetornarExcecao()
    {
        $this->objLexer->consume();
        $bolExcecaoLancada = false;
        try {
            $this->objLexer->match("X");
        } catch (\Exception $e) {
            $this->assertEquals("Esperava o caractere 'E' mas obteve o caractere 'X'.", $e->getMessage());
            $bolExcecaoLancada = true;
        }
        if (!$bolExcecaoLancada) {
            $this->fail("Uma exceção deve ser lançada.");
        }
    }

    public function testSeCaractereInformadoNoMatchForOEsperadoDeveConsumirOProximoCaractere()
    {
        $this->objLexer->consume();
        $this->objLexer->match("E");
        $this->assertEquals("L", $this->objLexer->getStrCharacter());
    }
}
