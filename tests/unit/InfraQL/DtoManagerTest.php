<?php
namespace InfraQL;

class DtoManagerTest extends \Codeception\Test\Unit
{

    protected $dtoManager;

    protected function _before()
    {
        $this->dtoManager = new DtoManager();
    }

    protected function _after()
    {}

    public function testQualquer()
    {
        $this->assertEquals(0, $this->dtoManager->retornarValorQualquer());
    }
}