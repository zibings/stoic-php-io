## ConsoleHelper Class
The `ConsoleHelper` class provides some general functionality that is common
to see when dealing with command line input/output (stdin/stdout).

### Methods
- [public] `__construct($argv, $forceCli)` -> Instantiates a new ConsoleHelper object with option to force CLI mode
- [public] `compareArg($key, $value, $caseInsensitive)` -> Compares an argument's value by key, optionally case insensitive
- [public] `compareArgAt($index, $value, $caseInsensitive)` -> Compares an argument's at the given index, optionally case insensitive
- [public] `get($characters)` -> Retrieves `$characters` from STDIN, defaults to 1
- [public] `getLine()` -> Retrieves an entire line from STDIN
- [public] `getParameterWithDefault()` -> Attempts to retrieve an argument's value using both short and long versions of its name
- [public] `getQueriedInput($query, $defaultValue, $errorMessage, $maxTries, $validation, $sanitation)` -> Queries a user to respond with a value
- [public] `getSelf()` -> Returns the name of the script being called
- [public] `hasArg($key, $caseInsensitive)` -> Checks if the given key exists, optionally case insensitive
- [public] `hasShortLongArg($short, $log, $caseInsensitive)` -> Checks if the given argument exists by either short or long versions, optionally case insensitive
- [public] `isCLI()` -> Returns whether or not this PHP runtime invocation was command line or not (overridden by $forceCli in ctor)
- [public] `isNaturalCLI()` -> Returns whether or not this PHP runtime invocation was command line or not (ignores $forceCli in ctor)
- [public] `numArgs()` -> Returns the number of parsed arguments
- [public] `parameters($asAssociative, $caseSensitive)` -> Returns the argument collection as regular or associative array, optionally case sensitive
- [protected] `parseParams($args, $caseInsensitive)` -> Parses a collection of arguments into useful arrays
- [public] `put($buf)` -> Outputs the buffer to STDOUT
- [public] `putLine($buf)` -> Outputs the buffer followed by a newline to STDOUT

### getQueriedInput()
The `ConsoleHelper::getQueriedInput()` method encapsulates the common operation of querying a user for a value.

#### Arguments
- `string $query` -> Base prompt, without a colon (:)
- `mixed $defaultValue` -> Default value for input, provide null if no default value
- `string $errorMessage` -> Message to display to user when the response isn't correct
- `integer $maxTries` -> Maximum number of attempts user can make before the method exits as a failure
- `callable $validation` -> Callable method/function to provide validation of input (must return boolean true/false)
- `callable $sanitation` -> Callable method/function to provide sanitation of the validated input

#### Example
```php
<?php

	use Stoic\Utilities\ConsoleHelper;

	$ch = new ConsoleHelper($argv);
	$name = $ch->qetQueriedInput(
		"Please enter your name", // $query
		null, // $defaultValue
		"You didn't provide a name", // $errorMessage
		3, // $maxTries
		function ($input) { return !empty($input) && strlen($input) > 3; }, // validation callable
		function ($input) { return trim($input); } // sanitation callable
	);

	/*
	 * Will prompt the user up to 3 times to enter their non-empty 4+ character long name...
	 *
	 *  Please enter your name: Andrew
	 *  Hello, Andrew!
	 *
	 */

	if ($name->isGood()) {
		$ch->putLine("Hello, {$name->getResults()[0]}!");
	}
```