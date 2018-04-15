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
        $manager = new DtoBuilder($sql);
        $dto = $manager->gerarDto($sql);
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
        $manager = new DtoBuilder($sql);
        $dto = $manager->gerarDto($sql);
        $this->assertFalse($dto->getRetTodosFoiChamado());
    }
}