<?php

namespace Violin\Validator;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Violin\Rules\All;
use Violin\Support\MessageBag;

class Validator
{
    /**
     * All rule messages
     *
     * @var array
     */
    public $ruleMessages = [
        'required'  => '{field} is required',
        'int'       => '{field} must be a number',
        'ip'        => '{field} must be a valid IP address.',
        'bool'      => '{field} must be true/false',
        'alpha'     => '{field} must be letters only',
        'alphaDash' => '{field} must be letters, with - and _ permitted.',
        'alnum'     => '{field} must be letters and numbers only.',
        'array'     => '{field} must be an array',
        'alnumDash' => '{field} must be letters and numbers, with - and _ permitted.',
        'email'     => '{field} must be a valid email address.',
        'activeUrl' => '{field} must be an active URL.',
        'max'       => '{field} is {input} but cannot be more than {value}.',
        'min'       => '{field} is {input} but cannot be less than {value}.',
        'url'       => '{field} must be a valid url.',
        'between'   => '{field} must be between [{value}, {value:1}].'
    ];

    /**
     * Default format for message output.
     * @var array
     */
    public $format = ['{field}', '{input}', '{value}'];

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
     * Replaces the formats defined in the message.
     *
     * @param  string $message
     * @param  array $arguments
     * @return string
     */
    protected function replace($message, $arguments)
    {
        if (count($arguments) > 3) {
            // If the arguments array is bigger than the format
            // array, then we need to consider that the rule has
            // multiple paramaters.
            $format = $this->format;

            for ($i = 2; $i < count($arguments); $i++) {
                $format[] = '{value:' . ($i - 1) . '}';
            }

            return str_replace($format, $arguments, $message);
        }

        return str_replace($this->format, $arguments, $message);
    }

    /**
     * Generates all the error messages and returns a MessageBag instance.
     *
     * @return Violin\Support\MessageBag
     */
    public function messages()
    {
        $messages = [];

        foreach ($this->errors as $field => $proprieties) {
            $messages[$field] = [];

            foreach ($proprieties['errors'] as $messageKey) {
                // If a field message has been set, we use this as preference.
                // Otherwise, we use the standard rule messages.
                $message = isset($this->fieldMessages[$field][$messageKey])
                    ? $this->fieldMessages[$field][$messageKey]
                    : $this->ruleMessages[$messageKey];

                // Initiate the replacement of the string.
                $message = $this->replace($message, $proprieties['args']);

                array_push($messages[$field], $message);
            }
        }

        return new MessageBag($messages);
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

        array_push($this->errors[$field]['errors'], $messageKey);
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
