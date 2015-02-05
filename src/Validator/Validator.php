<?php

namespace Violin\Validator;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Violin\Rules\All;

class Validator
{
    /**
     * All rule messages
     *
     * @var array
     */
    public $ruleMessages = [
        'required'  => '%s is required',
        'int'       => '%s must be a number',
        'ip'        => '%s must be a valid IP address.',
        'bool'      => '%s must be true/false',
        'alpha'     => '%s must be letters only',
        'alphaDash' => '%s must be letters, with - and _ permitted.',
        'alnum'     => '%s must be letters and numbers only.',
        'array'     => '%s must be an array',
        'alnumDash' => '%s must be letters and numbers, with - and _ permitted.',
        'email'     => '%s must be a valid email address.',
        'activeUrl' => '%s must be an active URL.',
        'max'       => '%s is %s but cannot be more than %s',
        'min'       => '%s is %s but cannot be less than %s',
        'url'       => '%s must be a valid url',
    ];

    /**
     * All field messages
     *
     * @var array
     */
    public $fieldMessages;

    /**
     * Accumulated errors
     *
     * @var array
     */
    protected $errors;

    /**
     * Custom defined rules
     *
     * @var array
     */
    protected $customRules;

    /**
     * Checks if an internal class for request validation exists,
     * and if so, runs it with arguments and reports an error.
     *
     * @param  string   $method
     * @param  array    $args
     *
     * @return void
     */
    public function __call($method, $args)
    {
        $rule = $this->extractRuleName($method);

        // Holds what method and arguments we want to call to validate.
        $toCall = null;

        // Check if a custom rule has been defined and if so, call it
        // and check if it's valid, adding an error if required.
        if (method_exists($this, 'validate_' . $method)) {
            $toCall = [$this, 'validate_' . $method];
        } else {
            $ruleClass = 'Violin\\Rules\\' . ucfirst($rule);

            if (class_exists($ruleClass)) {
                // Create a new instance of the internal rule class.
                $ruleClass = new $ruleClass();
                $toCall = [$ruleClass, 'run'];
            } else {
                $toCall = $this->customRules[$rule];
            }
        }

        // If we've found a method to call, call it with arguments
        // and check if the validation didn't pass.
        if ($toCall) {
            $this->callAndValidate($rule, $toCall, $args);
        }
    }

    /**
     * Call and validate a single rule and data
     *
     * @param  string $rule
     * @param  array $toCall
     * @param  array $args
     * @return void
     */
    protected function callAndValidate($rule, $toCall, $args)
    {
        // Flatten args to pass them correctly to the run method.
        $args = iterator_to_array(new RecursiveIteratorIterator(
            new RecursiveArrayIterator($args)), FALSE);

        $valid = call_user_func_array($toCall, $args);

        if (!$valid && $valid !== null) {
            $this->error($rule, $args);
        }
    }

    /**
     * Extract the rule name
     *
     * @param  string $method
     * @return string
     */
    protected function extractRuleName($method)
    {
        // Extract the possible internal class name
        // to look for a validation rule later.
        $rule = explode('validate_', $method);

        return end($rule);
    }

    /**
     * Adds a new rule
     *
     * @param string $name
     * @param Closure $callback
     */
    public function addRule($name, $callback)
    {
        $this->customRules[$name] = $callback;
    }

    /**
     * Gets the list of accumulated errors
     *
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Adds an error to the list of messages
     *
     * @param  string $messageKey
     * @param  array $args
     * @return void
     */
    public function error($messageKey, $args)
    {
        // Extract the field name from the arguments.
        $field = $args[0];

        $this->errors[$field]['args'] = $args;

        if (!array_key_exists('errors', $this->errors[$field])) {
            $this->errors[$field]['errors'] = [];
        }

        // If a field message has been set, we use this as preference.
        // Otherwise, we use the standard rule messages.
        $message = isset($this->fieldMessages[$field][$messageKey])
            ? $this->fieldMessages[$field][$messageKey]
            : $this->ruleMessages[$messageKey];

        // Extract the message from the ruleMessages array, passing in
        // the arguments to replace %s's if required, and return it.
        array_push($this->errors[$field]['errors'], vsprintf($message, $args));
    }

    /**
     * Checks if validation has passed.
     *
     * @return bool
     */
    public function valid()
    {
        return empty($this->errors);
    }

    /**
     * Adds a custom rule message.
     *
     * @param string $rule
     * @param string $message
     */
    public function addRuleMessage($rule, $message)
    {
        $this->ruleMessages[$rule] = $message;
    }

    /**
     * Adds custom rule messages.
     *
     * @param array $messages
     */
    public function addRuleMessages(array $messages)
    {
        $this->ruleMessages = $messages;
    }

    /**
     * Adds a custom field message.
     *
     * @param string $field
     * @param string $rule
     * @param string $message
     */
    public function addFieldMessage($field, $rule, $message)
    {
        $this->fieldMessages[$field][$rule] = $message;
    }

    /**
     * Adds custom field messages
     *
     * @param array $messages
     */
    public function addFieldMessages(array $messages)
    {
        $this->fieldMessages = $messages;
    }

    /**
     * Checks if rule name contains arguments part
     *
     * @param $name
     * @return bool
     */
    protected function ruleNeedsCallWithParameters($name)
    {
        return (bool)preg_match("/.+\([a-zA-Z0-9,'\" _]+\)/", $name);
    }

    /**
     * Gets array of parameters which needs to be apply.
     *
     * @param $rule
     * @return array|mixed|string
     */
    protected function getParametersArrayForRule($rule)
    {
        list($ruleName, $parametersWithBracketAtTheEnd) = explode('(', $rule);

        $parameters = rtrim($parametersWithBracketAtTheEnd, ')');
        $parameters = preg_replace('/\s+/', '', $parameters);
        $parameters = explode(',', $parameters);

        return $parameters;
    }

    /**
     * Get the name of the rule which has parameters.
     *
     * @param $rule
     * @return mixed
     */
    protected function getRuleNameForParametarizedRule($rule)
    {
        return explode('(', $rule)[0];
    }
}
