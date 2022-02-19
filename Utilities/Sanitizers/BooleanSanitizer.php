<?php

	namespace Stoic\Utilities\Sanitizers;

	/**
	 * Class BooleanSanitizer
	 *
	 * @package Stoic\IO
	 * @version 1.0.1
	 */
	class BooleanSanitizer implements SanitizerInterface {
		/**
		 * Convert the supplied variable into a boolean value.
		 *
		 * @param mixed $input The input that will be sanitized to a boolean value.
		 * @throws \Exception
		 * @return bool
		 */
		public function sanitize(mixed $input) : bool {
			$value = false;

			if (is_string($input)) {
				$input = strtolower($input);

				if ($input == 'true') {
					$value = true;
				}
			} else {
				$value = boolval($input);
			}

			return $value;
		}
	}
