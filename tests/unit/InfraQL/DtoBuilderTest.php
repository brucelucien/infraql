<?php
namespace InfraQL;

use FakeClass\PessoaDTO;

class DtoBuilderTest extends \Codeception\Test\Unit
{

    public function testDeveRetornarODtoInformadoNoFrom()
    {
        $sql = <<<SQL
            SELECT
                *
            FROM
                FakeClass\PessoaDTO
SQL;
        $builder = new DtoBuilder($sql);
        $dto = $builder->gerarDto();
        $this->assertInstanceOf(PessoaDTO::class, $dto);
    }

    public function testDeveExecutarRetTodosQuandoAsteriscoNoSelect()
    {
        $sql = <<<SQL
            SELECT
                *
            FROM
                FakeClass\PessoaDTO
SQL;
        $manager = new DtoBuilder($sql);
        $dto = $manager->gerarDto($sql);
        $this->assertTrue($dto->getRetTodosFoiChamado());
    }

    public function testNaoDeveExecutarRetTodosQuandoNaoHaAsteriscoNoSelect()
    {
        $sql = <<<SQL
            SELECT
                StrNome
            FROM
                FakeClass\PessoaDTO
SQL;
        $builder = new DtoBuilder($sql);
        $dto = $builder->gerarDto();
        $this->assertFalse($dto->getRetTodosFoiChamado());
    }

    public function testDeveExecutarRetStrNomeQuandoFazParteDoRetorno()
    {
        $sql = <<<SQL
            SELECT
                StrNome
            FROM
                FakeClass\PessoaDTO
SQL;
        $builder = new DtoBuilder($sql);
        $dto = $builder->gerarDto();
        $this->assertTrue($dto->getRetStrNomeFoiChamado());
    }

    public function testDeveExecutarRetStrSinAtivoQuandoFazParteDoRetorno()
    {
        $sql = <<<SQL
            SELECT
                StrSinAtivo
            FROM
                FakeClass\PessoaDTO
SQL;
        $builder = new DtoBuilder($sql);
        $dto = $builder->gerarDto();
        $this->assertTrue($dto->getRetStrSinAtivoFoiChamado());
    }

    public function testNaoDeveExecutarStrNomeQuandoNaoFazParteDoRetorno()
    {
        $sql = <<<SQL
            SELECT
                StrSinAtivo
            FROM
                FakeClass\PessoaDTO
SQL;
        $builder = new DtoBuilder($sql);
        $dto = $builder->gerarDto();
        $this->assertFalse($dto->getRetStrNomeFoiChamado());
    }

    public function testDeveExecutarRetStrSinAtivoEStrNomeERetStrSexoQuandoFazParteDoRetorno()
    {
        $sql = <<<SQL
            SELECT
                StrSinAtivo,
                StrNome,
                StrSexo
            FROM
                FakeClass\PessoaDTO
SQL;
        $builder = new DtoBuilder($sql);
        $dto = $builder->gerarDto();
        $this->assertTrue($dto->getRetStrSinAtivoFoiChamado());
        $this->assertTrue($dto->getRetStrNomeFoiChamado());
        $this->assertTrue($dto->getRetStrSexoFoiChamado());
    }

    public function testDeveExecutarSetDistinctParaSelectDeUmCampo()
    {
        $sql = <<<SQL
            SELECT DISTINCT
                StrSinAtivo
            FROM
                FakeClass\PessoaDTO
SQL;
        $builder = new DtoBuilder($sql);
        $dto = $builder->gerarDto();
        $this->assertTrue($dto->getSetDistinctFoiChamado());
    }

    public function testDeveIdentificarDistinctComMaisDeUmCampoNoSelect()
    {
        $sql = <<<SQL
            SELECT DISTINCT
                StrSinAtivo,
                StrNome,
                StrSexo
            FROM
                FakeClass\PessoaDTO
SQL;
        $builder = new DtoBuilder($sql);
        $dto = $builder->gerarDto();
        $this->assertTrue($dto->getRetStrSinAtivoFoiChamado());
        $this->assertTrue($dto->getRetStrNomeFoiChamado());
        $this->assertTrue($dto->getRetStrSexoFoiChamado());
        $this->assertTrue($dto->getSetDistinctFoiChamado());
    }

    public function testDeveIdentificarAtribuicaoSimplesTipoStringNoWhere()
    {
        $sql = <<<SQL
            SELECT
                StrNome
            FROM
                FakeClass\PessoaDTO
            WHERE
                StrSinAtivo = 'S'
SQL;
        $builder = new DtoBuilder($sql);
        $dto = $builder->gerarDto();
        $this->assertEquals('S', $dto->getStrSinAtivo());
    }

    public function testDeveIdentificarAtribuicaoSimplesTipoNumeroNoWhere()
    {
        $sql = <<<SQL
            SELECT
                StrNome
            FROM
                FakeClass\PessoaDTO
            WHERE
                NumIdade = 42
SQL;
        $builder = new DtoBuilder($sql);
        $dto = $builder->gerarDto();
        $this->assertEquals(42, $dto->getNumIdade());
    }
}