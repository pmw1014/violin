<?php

use Violin\Violin;

class ValidatorTest extends PHPUnit_Framework_TestCase
{

    public $v;

    public function setUp()
    {
        $this->v = new Violin;
    }

    public function testBasicValidationWithValidData()
    {
        $this->v->validate(['name' => 'billy'], ['name' => 'required']);
        $this->assertTrue($this->v->valid());
    }

    public function testBasicValidationWithInvalidData()
    {
        $this->v->validate(['name' => ''], ['name' => 'required']);
        $this->assertFalse($this->v->valid());
    }

    public function testCustomFieldMessage()
    {
        $message = 'You need to enter a username to sign up.';
        $this->v->addFieldMessage('username', 'required', $message);
        $this->v->validate(['username' => ''], ['username' => 'required']);
        $this->assertFalse($this->v->valid());
        $this->assertEquals($message, $this->v->messages()->first('username'));
    }

    public function testCustomRule()
    {
        $this->v->addRuleMessage('isBanana', '{field} expects banana, found "{value}" instead.');

        $this->v->addRule('isBanana', function($field, $value) {
            return $value === 'banana';
        });

        $this->v->validate(['name' => 'billy'], ['name' => 'isBanana']);

        $this->assertFalse($this->v->valid());
    }

    public function testCustomRuleMessage()
    {
        $this->v->addRuleMessage('required', 'You better fill in the {field} field, or else.');

        $this->v->validate(['name' => ''], ['name' => 'required']);

        $this->assertEquals('You better fill in the name field, or else.', $this->v->messages()->first('name'));
    }

    public function testParameters()
    {
        $this->v->addFieldMessages([
            'age' => [
                'max' => '{field} is {input} but cannot be more than {value}.'
            ]
        ]);

        $this->v->validate(['age' => 101], ['age' => 'max(100)']);
        $this->v->assertFalse($this->v->valid());
    }

    public function testMessageBag()
    {
        $this->v->addFieldMessage('age', 'min', 'Your {field} should be at least higher than {value}.');
        $this->v->validate(['name' => '', 'age' => 9], ['name' => 'required', 'age' => 'required|int|min(10)']);
        $this->assertTrue($this->v->messages()->has('age'));
        $this->assertEquals(2, count($this->v->messages()->all()));
        $this->assertEquals('Your age should be at least higher than 10.', $this->v->messages()->first('age'));
        $this->assertEquals(['name', 'age'], $this->v->messages()->keys());
    }

}
