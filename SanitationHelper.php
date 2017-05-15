<?php

	namespace Stoic\IO;

	use Stoic\IO\Sanitizers\SanitizerInterface;
	use Stoic\IO\Sanitizers\BooleanSanitizer;
	use Stoic\IO\Sanitizers\IntegerSanitizer;
	use Stoic\IO\Sanitizers\FloatSanitizer;
	use Stoic\IO\Sanitizers\StringSanitizer;

	/**
	 * Class SanitationHelper
	 *
	 * @package Stoic\IO
	 * @version 1.0.0
	 */
	class SanitationHelper {

		const BOOLEAN = 'bool';
		const INTEGER = 'int';
		const FLOAT   = 'float';
		const STRING  = 'string';

		/**
		 * List of all the default sanitizer classes.
		 *
		 * @var string[]
		 */
		protected $_defaults = array(
			self::BOOLEAN => BooleanSanitizer::class,
			self::INTEGER => IntegerSanitizer::class,
			self::FLOAT   => FloatSanitizer::class,
			self::STRING  => StringSanitizer::class,
		);

		/**
		 * List of all the instantiated sanitizers.
		 *
		 * @var SanitizerInterface[]
		 */
		protected $_sanitizers = array();

		/**
		 * SanitationHelper constructor.
		 *
		 * Adds all the default sanitizers.
		 */
		public function __construct() {
			foreach ($this->_defaults as $name => $class) {
				$this->addSanitizer($name, $class);
			}
		}

		/**
		 * Add a new sanitation type.
		 *
		 * @param string $key       The key, which is a string value, that described the sanitizer being added.
		 * @param string $sanitizer The instance of the sanitizer class, or a fully qualified domain name of the class.
		 *
		 * @return $this
		 */
		public function addSanitizer($key, $sanitizer) {
			if (is_object($sanitizer) && ($sanitizer instanceof SanitizerInterface)) {
				$this->_sanitizers[$key]= $sanitizer;

			} else if (is_string($sanitizer) && class_exists($sanitizer) && class_implements($sanitizer, SanitizerInterface::class)) {
				$this->_sanitizers[$key] = new $sanitizer();
			}

			return $this;
		}

		/**
		 * Check whether a sanitation type exists.
		 *
		 * @param string $key The key, which is a string value, of the sanitizer that is being searched for.
		 *
		 * @return bool
		 */
		public function hasSanitizer($key) {
			return isset($this->_sanitizers[$key]);
		}

		/**
		 * Converts the supplied input to a specific value.
		 *
		 * @param mixed  $input The value that is going to be sanitized.
		 * @param string $key   The key, which is a string value, for the sanitizer that will be used.
		 *
		 * @return mixed
		 */
		public function sanitize($input, $key) {
			if (!$this->hasSanitizer($key)) {
				return $input;
			}

			return $this->_sanitizers[$key]->sanitize($input);
		}
	}
