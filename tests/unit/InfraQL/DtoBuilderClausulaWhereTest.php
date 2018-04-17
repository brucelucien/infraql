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
        $this->assertEquals('S', $dto->getStrSinAtivo());
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
        $this->assertEquals(42, $dto->getNumIdade());
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
        $this->assertEquals($numIdade, $dto->getNumIdade());
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
        $this->assertEquals($numIdade, $dto->getNumIdade());
    }
}