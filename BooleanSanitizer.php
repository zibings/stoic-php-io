<?php

	namespace Stoic\Sanitation;

	/**
	 * Class BooleanSanitizer
	 *
	 * @package Stoic\Sanitation
	 * @version 1.0.0
	 */
	class BooleanSanitizer implements SanitizerInterface {

		/**
		 * Convert the supplied variable into a boolean value.
		 *
		 * @param mixed $input The input that will be sanitized to a boolean value.
		 *
		 * @return boolean
		 */
		public function sanitize($input) {
			$value = false;

			try {
				if (is_string($input)) {
					$input = strtolower($input);

					if ($input == 'false') {
						$value = false;

					} else if ($input == 'true') {
						$value = true;
					}
				} else {
					$value = (bool) $input;
				}
			} catch (\Exception $ex) {
				$value = false;
			}

			return $value;
		}
	}
