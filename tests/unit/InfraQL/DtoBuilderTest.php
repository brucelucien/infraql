<?php
namespace InfraQL;

use FakeClass\PessoaDTO;

class DtoBuilderTest extends \Codeception\Test\Unit
{

    public function testDeveRetornarODtoInformadoNoFrom()
    {
        $query = <<<QUERY
            SELECT
                *
            FROM
                FakeClass\PessoaDTO
QUERY;
        $builder = new DtoBuilder($query);
        $dto = $builder->gerarDto();
        $this->assertInstanceOf(PessoaDTO::class, $dto);
    }

    public function testDeveExecutarRetTodosQuandoAsteriscoNoSelect()
    {
        $query = <<<QUERY
            SELECT
                *
            FROM
                FakeClass\PessoaDTO
QUERY;
        $manager = new DtoBuilder($query);
        $dto = $manager->gerarDto($query);
        $this->assertTrue($dto->getRetTodosFoiChamado());
    }

    public function testNaoDeveExecutarRetTodosQuandoNaoHaAsteriscoNoSelect()
    {
        $query = <<<QUERY
            SELECT
                StrNome
            FROM
                FakeClass\PessoaDTO
QUERY;
        $builder = new DtoBuilder($query);
        $dto = $builder->gerarDto();
        $this->assertFalse($dto->getRetTodosFoiChamado());
    }

    public function testDeveExecutarRetStrNomeQuandoFazParteDoRetorno()
    {
        $query = <<<QUERY
            SELECT
                StrNome
            FROM
                FakeClass\PessoaDTO
QUERY;
        $builder = new DtoBuilder($query);
        $dto = $builder->gerarDto();
        $this->assertTrue($dto->getRetStrNomeFoiChamado());
    }

    public function testDeveExecutarRetStrSinAtivoQuandoFazParteDoRetorno()
    {
        $query = <<<QUERY
            SELECT
                StrSinAtivo
            FROM
                FakeClass\PessoaDTO
QUERY;
        $builder = new DtoBuilder($query);
        $dto = $builder->gerarDto();
        $this->assertTrue($dto->getRetStrSinAtivoFoiChamado());
    }

    public function testNaoDeveExecutarStrNomeQuandoNaoFazParteDoRetorno()
    {
        $query = <<<QUERY
            SELECT
                StrSinAtivo
            FROM
                FakeClass\PessoaDTO
QUERY;
        $builder = new DtoBuilder($query);
        $dto = $builder->gerarDto();
        $this->assertFalse($dto->getRetStrNomeFoiChamado());
    }

    public function testDeveExecutarRetStrSinAtivoEStrNomeERetStrSexoQuandoFazParteDoRetorno()
    {
        $query = <<<QUERY
            SELECT
                StrSinAtivo,
                StrNome,
                StrSexo
            FROM
                FakeClass\PessoaDTO
QUERY;
        $builder = new DtoBuilder($query);
        $dto = $builder->gerarDto();
        $this->assertTrue($dto->getRetStrSinAtivoFoiChamado());
        $this->assertTrue($dto->getRetStrNomeFoiChamado());
        $this->assertTrue($dto->getRetStrSexoFoiChamado());
    }

    public function testDeveExecutarSetDistinctParaSelectDeUmCampo()
    {
        $query = <<<QUERY
            SELECT DISTINCT
                StrSinAtivo
            FROM
                FakeClass\PessoaDTO
QUERY;
        $builder = new DtoBuilder($query);
        $dto = $builder->gerarDto();
        $this->assertTrue($dto->getSetDistinctFoiChamado());
    }

    public function testDeveIdentificarDistinctComMaisDeUmCampoNoSelect()
    {
        $query = <<<QUERY
            SELECT DISTINCT
                StrSinAtivo,
                StrNome,
                StrSexo
            FROM
                FakeClass\PessoaDTO
QUERY;
        $builder = new DtoBuilder($query);
        $dto = $builder->gerarDto();
        $this->assertTrue($dto->getRetStrSinAtivoFoiChamado());
        $this->assertTrue($dto->getRetStrNomeFoiChamado());
        $this->assertTrue($dto->getRetStrSexoFoiChamado());
        $this->assertTrue($dto->getSetDistinctFoiChamado());
    }

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
}