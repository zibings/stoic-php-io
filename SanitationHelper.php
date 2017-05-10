<?php

	namespace Stoic\Input;

	use Stoic\Input\Sanitizers\SanitizerInterface;

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
		protected $_sanitizers = array();

		/**
		 * Add a new sanitation type.
		 *
		 * @param string $key       The key, which is a string value, that described the sanitizer being added.
		 * @param string $sanitizer The instance of the sanitizer class, or a fully qualified domain name of the class.
		 *
		 * @return $this
		 */
		public function addSanitizer($key, $sanitizer) {
			if (is_object($sanitizer) && ($sanitizer instanceof SanitizerInterface::class)) {
				$this->_sanitizers[$key] = $sanitizer;

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
