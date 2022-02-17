<?php

	namespace Stoic\Utilities\Sanitizers;

	/**
	 * Class FloatSanitizer
	 *
	 * @package Stoic\IO
	 * @version 1.1.0
	 */
	class FloatSanitizer implements SanitizerInterface {
		/**
		 * Convert the supplied variable into a float value.
		 *
		 * @param mixed $input The input that will be sanitized to a float value.
		 * @throws \Exception
		 * @return float
		 */
		public function sanitize(mixed $input) : float {
			$value = 0.00;

			if (is_object($input)) {
				$props = get_object_vars($input);
				$value = count($props);
				$value = floatval($value);
			} else if (is_array($input)) {
				$value = count($input);
				$value = floatval($value);
			} else if (is_string($input) && !ctype_digit(str_replace('.', '', $input))) {
				$value = strlen($input);
				$value = floatval($value);
			} else {
				$value = floatval($input);
			}

			return $value;
		}
	}
