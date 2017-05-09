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
	}