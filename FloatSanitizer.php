<?php

	namespace Stoic\Sanitation;

	/**
	 * Class FloatSanitizer
	 *
	 * @package Stoic\Sanitation
	 * @version 1.0.0
	 */
	class FloatSanitizer implements SanitizerInterface {

		/**
		 * Convert the supplied variable into a float value.
		 *
		 * @param mixed $input The input that will be sanitized to a float value.
		 *
		 * @return float
		 */
		public function sanitize($input) {
			try {
				if (is_object($input)) {
					$props = get_object_vars($input);
					$value = count($props);

				} else if (is_array($input)) {
					$value = count($input);

					// We are removing the period from the input so that strings
					// which contain float values aren't processed as strings.
				} else if (is_string($input) && !ctype_digit(str_replace('.', '', $input))) {
					$value = strlen($input);

				} else {
					$value = floatval($input);
				}
			} catch (\Exception $ex) {
				$value = 0;
			}

			return $value;
		}
	}
