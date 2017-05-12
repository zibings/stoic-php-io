<?php

	namespace Stoic\Input\Sanitizers;

	/**
	 * Class BooleanSanitizer
	 *
	 * @package Stoic\Input
	 * @version 1.0.0
	 */
	class BooleanSanitizer implements SanitizerInterface {

		/**
		 * Convert the supplied variable into a boolean value.
		 *
		 * @param mixed $input The input that will be sanitized to a boolean value.
		 *
		 * @throws \Exception
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
					$value = boolval($input);
				}
			} catch (\Exception $ex) {
				throw $ex;
			}

			return $value;
		}
	}
