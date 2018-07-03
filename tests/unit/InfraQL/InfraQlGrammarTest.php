<?php
namespace InfraQL;

class InfraQlGrammarTest extends \Codeception\Test\Unit
{

    protected $objInfraQlGrammar;

    protected function _before()
    {
        $this->objInfraQlGrammar = new InfraQlGrammar("");
    }

    protected function _after()
    {
        $this->objInfraQlGrammar = null;
    }

    public function testXXXX()
    {}
}