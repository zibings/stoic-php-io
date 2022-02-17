<?php

	namespace Stoic\Utilities\Sanitizers;

	/**
	 * Class IntegerSanitizer
	 *
	 * @package Stoic\IO
	 * @version 1.1.0
	 */
	class IntegerSanitizer implements SanitizerInterface {
		/**
		 * Convert the supplied variable into an integer value.
		 *
		 * @param mixed $input The input that will be sanitized to an integer value.
		 * @throws \Exception
		 * @return int
		 */
		public function sanitize(mixed $input) : int {
			$value = 0;

			if (is_object($input)) {
				$props = get_object_vars($input);
				$value = count($props);
			} else if (is_array($input)) {
				$value = count($input);
			} else if (is_string($input) && !ctype_digit(str_replace('.', '', $input))) {
				$value = strlen($input);
			} else if (is_float($input)) {
				$value = intval(round($input));
			} else {
				$value = intval($input);
			}

			return $value;
		}
	}
