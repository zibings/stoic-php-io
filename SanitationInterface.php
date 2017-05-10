<?php

	namespace Stoic\Input;

	/**
	 * Interface SanitationInterface
	 *
	 * @package Stoic\Input
	 * @version 1.0.0
	 */
	interface SanitationInterface {

		/**
		 * Add a new sanitation type.
		 *
		 * @param string $key       The key, which is a string value, that described the sanitizer being added.
		 * @param string $sanitizer The instance of the sanitizer class, or a fully qualified domain name of the class.
		 *
		 * @return $this
		 */
		public function addSanitizer($key, $sanitizer);

		/**
		 * Check whether a sanitation type exists.
		 *
		 * @param string $key The key, which is a string value, of the sanitizer that is being searched for.
		 *
		 * @return bool
		 */
		public function hasSanitizer($key);

		/**
		 * Converts the supplied input to a specific value.
		 *
		 * @param mixed  $input The value that is going to be sanitized.
		 * @param string $key   The key, which is a string value, for the sanitizer that will be used.
		 *
		 * @return mixed
		 */
		public function sanitize($input, $key);
	}