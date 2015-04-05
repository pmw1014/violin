<?php

use Violin\Violin;

class ValidatorTest extends PHPUnit_Framework_TestCase
{
    protected $v;

    public function setUp()
    {
        $this->v = new Violin;
    }

    public function testBasicValidValidation()
    {
        $this->v->validate([
            'first_name'     => ['Billy', 'required|alpha|max(20)'],
            'last_name'      => ['Garrett', 'required|alpha|max(20)'],
            'email'          => ['billy@codecourse.com', 'required|email'],
            'password'       => ['ilovecats', 'required'],
            'password_again' => ['ilovecats', 'required|matches(password)']
        ]);

        $this->assertTrue($this->v->passes());
        $this->assertFalse($this->v->fails());
    }

    public function testBasicInvalidValidation()
    {
        $this->v->validate([
            'first_name'     => ['Billy', 'required|alpha|max(20)'],
            'last_name'      => ['', 'required|alpha|max(20)'],
            'email'          => ['billy@codecourse', 'required|email'],
            'password'       => ['ilovecats', 'required'],
            'password_again' => ['ilovecatsanddogs' , 'required|matches(password)']
        ]);

        $this->assertTrue($this->v->fails());
        $this->assertFalse($this->v->passes());
    }

    public function testRuleMessage()
    {
        $this->v->addRuleMessage('required', 'This field is required!');

        $this->v->validate([
            'username' => ['', 'required']
        ]);

        $this->assertEquals(
            $this->v->errors()->first('username'),
            'This field is required!'
        );
    }

    public function testRuleMessages()
    {
        $this->v->addRuleMessages([
            'required' => 'This field is required!',
            'alpha'    => 'Only alpha characters please!',
            'email'    => 'Enter a valid email!'
        ]);

        $this->v->validate([
            'username' => ['', 'required|alpha'],
            'email'    => ['notanemail', 'required|email']
        ]);

        $errors = $this->v->errors();

        $this->assertEquals(
            $errors->get('username'),
            ['This field is required!', 'Only alpha characters please!']
        );

        $this->assertEquals(
            $errors->first('email'),
            'Enter a valid email!'
        );
    }

    public function testFieldMessage()
    {
        $this->v->addFieldMessage('username', 'required', 'We need a username, please.');

        $this->v->validate([
            'username' => ['', 'required']
        ]);

        $this->assertEquals(
            $this->v->errors()->first('username'),
            'We need a username, please.'
        );
    }

    public function testFieldMessages()
    {
        $this->v->addFieldMessages([
            'username' => [
                'required' => 'We need a username, please.',
                'alpha' => 'Alpha characters in that username only, please.',
            ],
            'email' => [
                'required' => 'How do you expect us to contact you without an email?'
            ]
        ]);

        $this->v->validate([
            'username' => ['', 'required|alpha'],
            'email' => ['', 'required|email']
        ]);

        $errors = $this->v->errors();

        $this->assertEquals(
            $errors->get('username'),
            ['We need a username, please.', 'Alpha characters in that username only, please.']
        );

        $this->assertEquals(
            $errors->first('email'),
            'How do you expect us to contact you without an email?'
        );
    }

    public function testPassingCustomRule()
    {
        $this->v->addRule('isbanana', function($value, $input, $args) {
            return $value === 'banana';
        });

        $this->v->validate([
            'fruit' => ['apple', 'isbanana']
        ]);

        $this->assertFalse($this->v->passes());
    }

    public function testFailingCustomRule()
    {
        $this->v->addRule('isbanana', function($value, $input, $args) {
            return $value === 'banana';
        });

        $this->v->validate([
            'fruit' => ['banana', 'isbanana']
        ]);

        $this->assertTrue($this->v->passes());
    }

    public function testMultipleCustomRules()
    {
        $this->v->addRule('isbanana', function($value, $input, $args) {
            return $value === 'banana';
        });

        $this->v->addRule('isapple', function($value, $input, $args) {
            return $value === 'apple';
        });

        $this->v->validate([
            'fruit_one' => ['banana', 'isbanana'],
            'fruit_two' => ['apple', 'isapple']
        ]);

        $this->assertTrue($this->v->passes());
    }

    public function testPassingCustomRuleWithArguments()
    {
        $this->v->addRule('isoneof', function($value, $input, $args) {
            return in_array($value, $args);
        });

        $this->v->validate([
            'items' => ['seeds', 'isoneof(seeds, nuts, fruit)']
        ]);

        $this->assertTrue($this->v->passes());
    }

    public function testFailingCustomRuleWithArguments()
    {
        $this->v->addRule('isoneof', function($value, $input, $args) {
            return in_array($value, $args);
        });

        $this->v->validate([
            'items' => ['burger', 'isoneof(seeds, nuts, fruit)']
        ]);

        $this->assertFalse($this->v->passes());
    }

    public function testValidationWithAliases()
    {
        $this->v->addFieldMessages([
            'username_box' => [
                'required' => 'We need a username in the {field} field, please.'
            ]
        ]);

        $this->v->validate([
            'username_box|Username' => ['', 'required'],
            'password' => ['secret', 'required|alpha']
        ]);

        $errors = $this->v->errors();

        $this->assertFalse($this->v->passes());
        $this->assertTrue($this->v->fails());
        $this->assertEquals(
            $errors->first('username_box'),
            'We need a username in the Username field, please.'
        );
    }
}
