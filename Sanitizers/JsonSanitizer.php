<?php

	namespace Stoic\IO\Sanitizers;

	/**
	 * Class BooleanSanitizer
	 *
	 * @package Stoic\Input
	 * @version 1.0.0
	 */
	class JsonSanitizer implements SanitizerInterface {

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
			try {
				if (($value = json_decode($input)) === null) {
					if (($error = json_last_error_msg()) === null) {
						$error = "Unable to decode the json.";
					}

					throw new \Exception($error);
				}
			} catch (\Exception $ex) {
				throw $ex;
			}

			return $value;
		}
	}
