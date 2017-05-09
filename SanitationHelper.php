<?php

	namespace Stoic\Sanitation;

	/**
	 * Class SanitationHelper
	 *
	 * @package Stoic\Sanitation
	 * @version 1.0.0
	 */
	class SanitationHelper implements SanitationInterface {
		const BOOLEAN = 'boolean';
		const INTEGER = 'integer';
		const STRING  = 'string';
		const FLOAT   = 'float';

		/**
		 * List of all the registered sanitizers.
		 *
		 * @var string[]
		 */
		protected static $_registered = array(
			self::BOOLEAN => BooleanSanitizer::class,
			self::INTEGER => IntegerSanitizer::class,
			self::STRING  => StringSanitizer::class,
			self::FLOAT   => FloatSanitizer::class
		);

		/**
		 * List of all the instantiated sanitizers.
		 *
		 * @var SanitizerInterface[]
		 */
		protected static $_sanitizers = array();

		/**
		 * Singleton instance of the sanitation helper.
		 *
		 * @var SanitationHelper
		 */
		protected static $_instance = null;

		/**
		 * Returns the singleton instance of the sanitation helper.
		 *
		 * @return SanitationHelper
		 */
		public static function &getInstance() {
			if (is_null(self::$_instance) || !is_object(self::$_instance)) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Converts the supplied input into a boolean value.
		 *
		 * @param mixed $input The variable that will be converted to a boolean value.
		 *
		 * @return boolean
		 */
		public function boolean($input) {
			return $this->sanitize($input, self::BOOLEAN);
		}

		/**
		 * Converts the supplied input into a string value.
		 *
		 * @param mixed $input The variable that will be converted to a string value.
		 *
		 * @return string
		 */
		public function string($input) {
			return $this->sanitize($input, self::STRING);
		}

		/**
		 * Converts the supplied input into an integer value.
		 *
		 * @param mixed $input The variable that will be converted to an integer value.
		 *
		 * @return integer
		 */
		public function integer($input) {
			return $this->sanitize($input, self::INTEGER);
		}

		/**
		 * Converts the supplied input into a float value.
		 *
		 * @param mixed $input The variable that will be converted to a float value.
		 *
		 * @return float
		 */
		public function float($input) {
			return $this->sanitize($input, self::FLOAT);
		}

		/**
		 * Converts the supplied input to a specific value.
		 *
		 * @param mixed  $input     The value that is going to be sanitized.
		 * @param string $sanitizer The name of the sanitizer type.
		 *
		 * @return mixed
		 */
		public function sanitize($input, $sanitizer) {

			// The sanitizer hasn't been initialized yet, or is not of the correct type.
			if (!isset(self::$_sanitizers[$sanitizer]) || !(self::$_sanitizers[$sanitizer] instanceof self::$_registered[$sanitizer])) {

				// Oh no! There is no registered sanitizer.
				if (!isset(self::$_registered[$sanitizer])) {
					return $input;
				}

				self::$_sanitizers[$sanitizer] = new self::$_registered[$sanitizer]();
			}

			return self::$_sanitizers[$sanitizer]->sanitize($input);
		}

		/**
		 * Registers a sanitizer into the system. If there is already a sanitizer that is
		 * registered to the supplied name, then it will be overridden.
		 *
		 * @param string $name  A name that describes the sanitation type.
		 * @param string $class The fully qualified domain name of the sanitation class.
		 *
		 * @return bool
		 */
		public static function registerSanitizer($name, $class) {
			if (!is_string($name) || !class_exists($class)) {
				return false;
			}

			if (!class_implements($class, SanitizerInterface::class)) {
				return false;
			}

			self::$_registered[$name] = $class;

			return true;
		}
	}
