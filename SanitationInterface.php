<?php

	namespace Stoic\Sanitation;

	/**
	 * Interface SanitationInterface
	 *
	 * @package Stoic\Sanitation
	 * @version 1.0.0
	 */
	interface SanitationInterface {

		/**
		 * Returns a singleton instance of the sanitation class.
		 *
		 * @return $this
		 */
		public static function &getInstance();

		/**
		 * Sanitize the input variable into the supplied sanitizer type.
		 *
		 * @param mixed  $input     The variable that will be converted to the supplied sanitizer type.
		 * @param string $sanitizer The name of the sanitizer to use for the conversion.
		 *
		 * @return mixed
		 */
		public function sanitize($input, $sanitizer);

		/**
		 * Registers a sanitizer class into the system. If a sanitizer with the same name
		 * already exists, then it will be overridden.
		 *
		 * @param string $name      A name that describes the sanitation type.
		 * @param string $class     The fully qualified domain name of the sanitizer class.
		 *
		 * @return mixed
		 */
		public static function registerSanitizer($name, $class);
	}