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
        $this->assertEquals(42, $dto->getNumIdade());
        $this->assertEquals('99988877766', $dto->getStrCpf());
        $this->assertEquals('Azul', $dto->getStrCorPreferida());
    }
    
    
    public function testDeveMapearOperadorLogicoOr()
    {
        $query = <<<QUERY
            SELECT
                *
            FROM
                FakeClass\LocalInstalacaoEprocDTO
            WHERE
                StrSigUf <> 'RS'
                OR StrTipoContexto <> 'D'
                OR StrTipoInstancia <> 'EST1'
                OR StrTipoAmbiente <> 'PN'
QUERY;
        $builder = new DtoBuilder($query);
        $dto = $builder->gerarDto();
        // TODO Testar essa atribuição do comentário abaixo
        /**
            $dto->adicionarCriterio(
              array('SigUf', 'TipoContexto', 'TipoInstancia', 'TipoAmbiente'),
              array(InfraDTO::$OPER_DIFERENTE, InfraDTO::$OPER_DIFERENTE, InfraDTO::$OPER_DIFERENTE, InfraDTO::$OPER_DIFERENTE),
              array($objPessoaUsuarioReplicado->sigUf, $objPessoaUsuarioReplicado->tipoContexto,$objPessoaUsuarioReplicado->tipoInstancia, $objPessoaUsuarioReplicado->tipoAmbiente),
              array(InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR, InfraDTO::$OPER_LOGICO_OR)
            );
         */
    }
    

}

