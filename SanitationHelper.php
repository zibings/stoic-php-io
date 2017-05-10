<?php

	namespace Stoic\Input;

	use Stoic\Input\Sanitizers\BooleanSanitizer;
	use Stoic\Input\Sanitizers\FloatSanitizer;
	use Stoic\Input\Sanitizers\IntegerSanitizer;
	use Stoic\Input\Sanitizers\SanitizerInterface;
	use Stoic\Input\Sanitizers\StringSanitizer;

	/**
	 * Class SanitationHelper
	 *
	 * @package Stoic\Input
	 * @version 1.0.0
	 */
	class SanitationHelper implements SanitationInterface {

		/**
		 * List of all the instantiated sanitizers.
		 *
		 * @var SanitizerInterface[]
		 */
		protected static $_sanitizers = array();

		/**
		 * Converts the supplied input into a boolean value.
		 *
		 * @param mixed $input The variable that will be converted to a boolean value.
		 *
		 * @return boolean
		 */
		public function boolean($input) {
			// @Todo: The default boolean sanitizer will need to be able to be overridden.
			return $this->sanitize($input, BooleanSanitizer::class);
		}

		/**
		 * Converts the supplied input into a string value.
		 *
		 * @param mixed $input The variable that will be converted to a string value.
		 *
		 * @return string
		 */
		public function string($input) {
			// @Todo: The default string sanitizer will need to be able to be overridden.
			return $this->sanitize($input, StringSanitizer::class);
		}

		/**
		 * Converts the supplied input into an integer value.
		 *
		 * @param mixed $input The variable that will be converted to an integer value.
		 *
		 * @return integer
		 */
		public function integer($input) {
			// @Todo: The default integer sanitizer will need to be able to be overridden.
			return $this->sanitize($input, IntegerSanitizer::class);
		}

		/**
		 * Converts the supplied input into a float value.
		 *
		 * @param mixed $input The variable that will be converted to a float value.
		 *
		 * @return float
		 */
		public function float($input) {
			// @Todo: The default float sanitizer will need to be able to be overridden.
			return $this->sanitize($input, FloatSanitizer::class);
		}

		/**
		 * Converts the supplied input to a specific value.
		 *
		 * @param mixed  $input The value that is going to be sanitized.
		 * @param string $class The name of the sanitizer type.
		 *
		 * @return mixed
		 */
		public function sanitize($input, $class) {
			if (!isset(self::$_sanitizers[$class])) {
				if (!class_exists($class)) {
					return $input;
				}

				if (!class_implements($class, SanitizerInterface::class)) {
					return $input;
				}

				self::$_sanitizers[$class] = new $class();
			}

			return self::$_sanitizers[$class]->sanitize($input);
		}
	}
