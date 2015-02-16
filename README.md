# violin

[![Build Status](https://travis-ci.org/alexgarrett/violin.svg?branch=master)](https://travis-ci.org/alexgarrett/violin)

Violin is an easy to use, highly customisable PHP validator.

**Note: This package is under heavy development and is not recommended for production.**

## Installing

Install using Composer.

```json
{
    "require": {
        "alexgarrett/violin": "1.*"
    }
}
```

## Basic usage

```php
use Violin\Violin;

$v = new Violin;

$v->validate([
    'name'  => 'billy',
    'age'   => 20
], [
    'name'  => 'required',
    'age'   => 'required|int'
]);

if($v->valid()) {
    echo 'Valid!';
} else {
    echo '<pre>', var_dump($v->messages()->all()), '</pre>';
}
```

## Adding custom rules

Adding custom rules is simple. If the closure returns false, the rule fails.

```php
$v->addRuleMessage('isBanana', '{field} expects banana, found "{input}" instead.');

$v->addRule('isBanana', function($field, $value) {
    return $value === 'banana';
});
```

## Adding custom error messages

You can add rule messages, or field messages for total flexibility.

### Adding a rule message

```php
$v->addRuleMessage('required', 'You better fill in the {field} field, or else.');
```

### Adding rule messages in bulk

```php
$v->addRuleMessages([
    'required' => 'You better fill in the {field} field, or else.',
    'int'      => 'The {field} needs to be an integer, but I found {input}.',
]);
```

### Adding a field message

Any field messages you add are preferred over any default or custom rule messages.

```php
$v->addFieldMessage('username', 'required', 'You need to enter a username to sign up.');
```

### Adding field messages in bulk

```php
$v->addFieldMessages([
    'username' => [
        'required' => 'You need to enter a username to sign up.'
    ],
    'age' => [
        'required' => 'I need your age.',
        'int'      => 'Your age needs to be an integer.',
    ]
]);
```

### Error output

See `examples/messages.php`.

## Extending the Violin class

You can extend Violin to implement your own validation class and add rules, custom rule messages and custom field messages.

```php
class Validator extends Violin\Violin
{
    protected $db;

    protected function __construct(Database $db)
    {
        $this->db = $db; // Some database dependency

        // You can add a custom rule message here if you like, or, you
        // could add it outside of this validation class when you
        // make use of your new Validator object.
        $this->addRuleMessage('usernameDoesNotExist', 'That username is taken');
    }

    // Prepend your validation rule name with validate_
    public function validate_usernameDoesNotExist($field, $value)
    {
        if($db->where('username', '=', $value)->count()) {
            return false;
        }
    }
}

$v = new Validator;

// ... and so on.
```

## Rules

This list of rules are **in progress**. Of course, you can always contribute to the project if you'd like to add more to the base ruleset.

#### activeUrl

If the URL provided is an active URL using checkdnsrr().

#### alnum

If the value is alphanumeric.

#### alnumDash

If the value is alphanumeric. Dashes and underscores are permitted.

#### alpha

If the value is alphabetic letters only.

#### alphaDash

If the value is alphabetic letters only. Dashes and underscores are permitted.

#### array

If the value is an array.

#### between(int, int)

Checks if the value is within the intervals defined.

#### bool

If the value is a boolean.

#### email

If the value is a valid email.

#### int

If the value is an integer, including integers within strings. 1 and '1' are both classed as integers.

#### ip

If the value is a valid IP address.

#### max(int)

Rule with parameter. Checks if the value is less or equals than parameter.

#### min(int)

Rule with parameter. Checks if the value is greater or equals than parameter.

#### required

If the value is present.

#### url

If the value is formatted as a valid URL.

## Contributing

Please file issues under GitHub, or submit a pull request if you'd like to directly contribute.
