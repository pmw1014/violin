<?php

require_once 'vendor/autoload.php';

use Violin\Violin;

class RulesTest extends PHPUnit_Framework_TestCase
{
    public $v;

    public function setUp()
    {
        $this->v = new Violin;
    }

    public function testBetween()
    {
        $this->v->validate(['age' => 10], ['age' => 'required|int|between(10, 20)']);
        $this->assertTrue($this->v->valid());

        $this->v->validate(['age' => 1], ['age' => 'required|int|between(10, 20)']);
        $this->assertFalse($this->v->valid());

        $this->v->validate(['age' => 1], ['age' => 'required|int|between(10, 20)']);
        $this->assertEquals('age must be between [10, 20].', $this->v->messages()->first('age'));
    }
}
