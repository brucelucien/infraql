<?php
namespace InfraQL;

class DtoBuilderClausulaWhereTest extends \Codeception\Test\Unit
{

    public function testDeveIdentificarAtribuicaoSimplesTipoStringNoWhere()
    {
        $query = <<<QUERY
            SELECT
                StrNome
            FROM
                FakeClass\PessoaDTO
            WHERE
                StrSinAtivo = 'S'
QUERY;
        $builder = new DtoBuilder($query);
        $dto = $builder->gerarDto();
        $this->assertEquals('SinAtivo', $dto->getVarAtributos()[0]);
        $this->assertEquals('=', $dto->getVarOperadoresAtributos()[0]);
        $this->assertEquals('S', $dto->getVarValoresAtributos()[0]);
        $this->assertEquals(array(), $dto->getVarOperadoresLogicos());
    }

    public function testDeveIdentificarAtribuicaoSimplesTipoNumeroNoWhere()
    {
        $query = <<<QUERY
            SELECT
                StrNome
            FROM
                FakeClass\PessoaDTO
            WHERE
                NumIdade = 42
QUERY;
        $builder = new DtoBuilder($query);
        $dto = $builder->gerarDto();
        $this->assertEquals('Idade', $dto->getVarAtributos()[0]);
        $this->assertEquals('=', $dto->getVarOperadoresAtributos()[0]);
        $this->assertEquals(42, $dto->getVarValoresAtributos()[0]);
        $this->assertEquals(array(), $dto->getVarOperadoresLogicos());
    }

    public function testDeveSerPossivelUsarUmParametroNaQuery()
    {
        $query = <<<QUERY
            SELECT
                StrNome
            FROM
                FakeClass\PessoaDTO
            WHERE
                NumIdade = :NUM_IDADE
QUERY;
        $numIdade = 42;
        $builder = new DtoBuilder($query);
        $builder->setParam('NUM_IDADE', $numIdade);
        $dto = $builder->gerarDto();
        $this->assertEquals('Idade', $dto->getVarAtributos()[0]);
        $this->assertEquals('=', $dto->getVarOperadoresAtributos()[0]);
        $this->assertEquals(42, $dto->getVarValoresAtributos()[0]);
        $this->assertEquals(array(), $dto->getVarOperadoresLogicos());
    }

    public function testDeveSerPossivelUsarUmParametroComDoisPontosNaQuery()
    {
        $query = <<<QUERY
            SELECT
                StrNome
            FROM
                FakeClass\PessoaDTO
            WHERE
                NumIdade = :NUM_IDADE
QUERY;
        $numIdade = 23;
        $builder = new DtoBuilder($query);
        $builder->setParam(':NUM_IDADE', $numIdade);
        $dto = $builder->gerarDto();
        $this->assertEquals('Idade', $dto->getVarAtributos()[0]);
        $this->assertEquals('=', $dto->getVarOperadoresAtributos()[0]);
        $this->assertEquals(23, $dto->getVarValoresAtributos()[0]);
        $this->assertEquals(array(), $dto->getVarOperadoresLogicos());
    }

    public function testDeveIdentificarUmAndEDoisParametros()
    {
        $query = <<<QUERY
            SELECT
                StrNome
            FROM
                FakeClass\PessoaDTO
            WHERE
                NumIdade = 42
                AND StrCpf = '99988877766'
                AND StrCorPreferida = 'Azul'
QUERY;
        $builder = new DtoBuilder($query);
        $dto = $builder->gerarDto();
        $this->assertEquals('Idade', $dto->getVarAtributos()[0]);
        $this->assertEquals('=', $dto->getVarOperadoresAtributos()[0]);
        $this->assertEquals(42, $dto->getVarValoresAtributos()[0]);
        $this->assertEquals('AND', $dto->getVarOperadoresLogicos()[0]);
        $this->assertEquals('Cpf', $dto->getVarAtributos()[1]);
        $this->assertEquals('=', $dto->getVarOperadoresAtributos()[1]);
        $this->assertEquals('99988877766', $dto->getVarValoresAtributos()[1]);
        $this->assertEquals('AND', $dto->getVarOperadoresLogicos()[1]);
        $this->assertEquals('CorPreferida', $dto->getVarAtributos()[2]);
        $this->assertEquals('=', $dto->getVarOperadoresAtributos()[2]);
        $this->assertEquals('Azul', $dto->getVarValoresAtributos()[2]);
    }

    public function testDeveMapearUsandoAdicionarCriterioAtributos()
    {
        $query = <<<QUERY
            SELECT
                *
            FROM
                FakeClass\LocalInstalacaoEprocDTO
            WHERE
                StrSigUf <> 'RS'
                OR StrTipoContexto = 'D'
                AND StrTipoInstancia <> 'EST1'
                OR StrTipoAmbiente = 'PN'
QUERY;
        $builder = new DtoBuilder($query);
        $dto = $builder->gerarDto();
        $this->assertEquals('SigUf', $dto->getVarAtributos()[0]);
        $this->assertEquals('TipoContexto', $dto->getVarAtributos()[1]);
        $this->assertEquals('TipoInstancia', $dto->getVarAtributos()[2]);
        $this->assertEquals('TipoAmbiente', $dto->getVarAtributos()[3]);
    }

    public function testDeveMapearUsandoAdicionarCriterioOperadoresComparacao()
    {
        $query = <<<QUERY
            SELECT
                *
            FROM
                FakeClass\LocalInstalacaoEprocDTO
            WHERE
                StrSigUf <> 'RS'
                OR StrTipoContexto = 'D'
                AND StrTipoInstancia <> 'EST1'
                OR StrTipoAmbiente = 'PN'
QUERY;
        $builder = new DtoBuilder($query);
        $dto = $builder->gerarDto();
        // Operadores de comparação
        $this->assertEquals('<>', $dto->getVarOperadoresAtributos()[0]);
        $this->assertEquals('=', $dto->getVarOperadoresAtributos()[1]);
        $this->assertEquals('<>', $dto->getVarOperadoresAtributos()[2]);
        $this->assertEquals('=', $dto->getVarOperadoresAtributos()[3]);
    }

    public function testDeveMapearUsandoAdicionarCriterioValores()
    {
        $query = <<<QUERY
            SELECT
                *
            FROM
                FakeClass\LocalInstalacaoEprocDTO
            WHERE
                StrSigUf <> 'RS'
                OR StrTipoContexto = 'D'
                AND StrTipoInstancia <> 'EST1'
                OR StrTipoAmbiente = 'PN'
QUERY;
        $builder = new DtoBuilder($query);
        $dto = $builder->gerarDto();
        $this->assertEquals('RS', $dto->getVarValoresAtributos()[0]);
        $this->assertEquals('D', $dto->getVarValoresAtributos()[1]);
        $this->assertEquals('EST1', $dto->getVarValoresAtributos()[2]);
        $this->assertEquals('PN', $dto->getVarValoresAtributos()[3]);
    }

    public function testDeveMapearUsandoAdicionarCriterioOperadoresLogicos()
    {
        $query = <<<QUERY
            SELECT
                *
            FROM
                FakeClass\LocalInstalacaoEprocDTO
            WHERE
                StrSigUf <> 'RS'
                OR StrTipoContexto = 'D'
                AND StrTipoInstancia <> 'EST1'
                OR StrTipoAmbiente = 'PN'
QUERY;
        $builder = new DtoBuilder($query);
        $dto = $builder->gerarDto();
        $this->assertEquals('OR', $dto->getVarOperadoresLogicos()[0]);
        $this->assertEquals('AND', $dto->getVarOperadoresLogicos()[1]);
        $this->assertEquals('OR', $dto->getVarOperadoresLogicos()[2]);
    }

    public function testDeveMapearUsandoAdicionarCriterioComCondicaoNumerica()
    {
        $query = <<<QUERY
            SELECT
                *
            FROM
                FakeClass\LocalInstalacaoEprocDTO
            WHERE
                StrSigUf <> 'RS'
                OR StrTipoContexto = 'D'
                AND NumIdProgramador <> 42
                OR StrTipoAmbiente = 'PN'
QUERY;
        $builder = new DtoBuilder($query);
        $dto = $builder->gerarDto();
        $this->assertEquals('IdProgramador', $dto->getVarAtributos()[2]);
        $this->assertEquals('<>', $dto->getVarOperadoresAtributos()[2]);
        $this->assertEquals(42, $dto->getVarValoresAtributos()[2]);
        $this->assertEquals('AND', $dto->getVarOperadoresLogicos()[1]);
    }

}
