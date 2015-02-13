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

    public function testAlnumDashRule()
    {
        $this->v->validate(['username' => 'violin-tests_1_alnum'], ['username' => 'alnumDash']);
        $this->assertTrue($this->v->valid());

        $this->v->validate(['username' => 'violinTests2Alnum'], ['username' => 'alnumDash']);
        $this->assertTrue($this->v->valid());

        $this->v->validate(['username' => 'violin-tests@3_alnum'], ['username' => 'alnumDash']);
        $this->assertFalse($this->v->valid());
    }

    public function testAlnumRule()
    {
        $this->v->validate(['username' => 'violin-tests_alnum'], ['username' => 'alnum']);
        $this->assertFalse($this->v->valid());

        $this->v->validate(['username' => 'violin'], ['username' => 'alnum']);
        $this->assertTrue($this->v->valid());

        $this->v->validate(['username' => 'violin-tests@alnum'], ['username' => 'alnum']);
        $this->assertFalse($this->v->valid());
    }

    public function testAlphaDashRule()
    {
        /*$this->v->validate(['username' => 'violin-tests_1_alphaDash'], ['username' => 'alphaDash']);
        $this->assertFalse($this->v->valid());

        $this->v->validate(['username' => 'violinTests_two-alphaDash'], ['username' => 'alphaDash']);
        $this->assertTrue($this->v->valid());

        $this->v->validate(['username' => 'violin-tests@_three_alphaDash'], ['username' => 'alphaDash']);
        $this->assertFalse($this->v->valid());

        $this->v->validate(['username' => 'violinTests'], ['username' => 'alphaDash']);
        $this->assertTrue($this->v->valid());*/
    }

    public function testAlphaRule()
    {
        $this->v->validate(['username' => 'violin-tests_1_alphaDash'], ['username' => 'alpha']);
        $this->assertFalse($this->v->valid());

        $this->v->validate(['username' => 'violinTests_two-alphaDash'], ['username' => 'alpha']);
        $this->assertFalse($this->v->valid());

        $this->v->validate(['username' => 'violin-tests@_three_alphaDash'], ['username' => 'alpha']);
        $this->assertFalse($this->v->valid());

        $this->v->validate(['username' => 'violinTests'], ['username' => 'alpha']);
        $this->assertTrue($this->v->valid());
    }

    public function testArrayRule()
    {
        // Check Issue #21 @https://github.com/alexgarrett/violin/issues/21
    }

    public function testBetweenRule()
    {
        $this->v->validate(['age' => 10], ['age' => 'between(10, 20)']);
        $this->assertTrue($this->v->valid());

        $this->v->validate(['age' => 1], ['age' => 'between(10, 20)']);
        $this->assertFalse($this->v->valid());

        $this->v->validate(['age' => 1], ['age' => 'between(10, 20)']);
        $this->assertEquals('age must be between [10, 20].', $this->v->messages()->first('age'));
    }

    public function testBoolRule()
    {
        $this->v->validate(['accept' => true], ['accept' => 'bool']);
        $this->assertTrue($this->v->valid());

        $this->v->validate(['accept' => 1], ['accept' => 'bool']);
        $this->assertFalse($this->v->valid());
    }

    public function testEmailRule()
    {
        $this->v->validate(['email' => 'email@example@example.com'], ['email' => 'email']);
        $this->assertFalse($this->v->valid());

        $this->v->validate(['email' => '#@%^%#$@#$@#.com'], ['email' => 'email']);
        $this->assertFalse($this->v->valid());

        $this->v->validate(['email' => 'plainaddress'], ['email' => 'email']);
        $this->assertFalse($this->v->valid());

        $this->v->validate(['email' => 'email@example.com'], ['email' => 'email']);
        $this->assertTrue($this->v->valid());
    }

    public function testIntRule()
    {
        $this->v->validate(['number' => 1.32], ['number' => 'int']);
        $this->assertFalse($this->v->valid());

        $this->v->validate(['number' => '1'], ['number' => 'int']);
        $this->assertTrue($this->v->valid());

        $this->v->validate(['number' => 1], ['number' => 'int']);
        $this->assertTrue($this->v->valid());
    }

    public function testIpRule()
    {
        $this->v->validate(['ip' => '42.42'], ['ip' => 'ip']);
        $this->assertFalse($this->v->valid());

        $this->v->validate(['ip' => '127.0.0.1'], ['ip' => 'ip']);
        $this->assertTrue($this->v->valid());
    }

    public function testMaxRule()
    {
        $this->v->validate(['number' => 101], ['number' => 'max(100)']);
        $this->assertFalse($this->v->valid());

        $this->v->validate(['number' => 100], ['number' => 'max(100)']);
        $this->assertTrue($this->v->valid());

        $this->v->validate(['number' => 10], ['number' => 'max(100)']);
        $this->assertTrue($this->v->valid());
    }

    public function testMinRule()
    {
        $this->v->validate(['number' => 101], ['number' => 'min(100)']);
        $this->assertTrue($this->v->valid());

        $this->v->validate(['number' => 100], ['number' => 'min(100)']);
        $this->assertTrue($this->v->valid());

        $this->v->validate(['number' => 10], ['number' => 'min(100)']);
        $this->assertFalse($this->v->valid());
    }

    public function testRequiredRule()
    {
        $this->v->validate(['name' => ' '], ['name' => 'required']);
        $this->assertFalse($this->v->valid());

        $this->v->validate(['name' => ''], ['name' => 'required']);
        $this->assertFalse($this->v->valid());

        $this->v->validate(['name' => 'Violin'], ['name' => 'required']);
        $this->assertTrue($this->v->valid());
    }

    public function testUrlRule()
    {
        $this->v->validate(['url' => 'http://www.example.com/space here.html'], ['url' => 'url']);
        $this->assertFalse($this->v->valid());

        $this->v->validate(['url' => 'www.example.com/main.html'], ['url' => 'url']);
        $this->assertFalse($this->v->valid());

        $this->v->validate(['url' => 'http://www.example.com/main.html'], ['url' => 'url']);
        $this->assertTrue($this->v->valid());
    }
}
