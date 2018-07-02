<?php
namespace InfraQL;

class TokenTest extends \Codeception\Test\Unit
{
    protected $objToken;

    protected function _before()
    {
        $this->objToken = new Token();
    }

    protected function _after()
    {
        $this->objToken = null;
    }

    public function testDeveTerType()
    {
        $numType = 42;
        $this->objToken->setNumType($numType);
        $this->assertEquals($numType, $this->objToken->getNumType());
    }

    public function testTypeIniciaComMenos1()
    {
        $this->assertEquals(-1, $this->objToken->getNumType());
    }

    public function testDeveTerText()
    {
        $strText = 'SELECT';
        $this->objToken->setStrText($strText);
        $this->assertEquals($strText, $this->objToken->getStrText());
    }

    public function testTypeIniciaComVazio()
    {
        $this->assertEquals("", $this->objToken->getStrText());
    }

    public function testDeveSerPossivelInstanciarComTypeEText()
    {
        $numType = 67;
        $strText = "FROM";
        $objToken = new Token($numType, $strText);
        $this->assertEquals($numType, $objToken->getNumType());
        $this->assertEquals($strText, $objToken->getStrText());
    }

}
