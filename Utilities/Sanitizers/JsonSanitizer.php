<?php

	namespace Stoic\Utilities\Sanitizers;

	/**
	 * Class BooleanSanitizer
	 *
	 * @package Stoic\IO
	 * @version 1.1.0
	 */
	class JsonSanitizer implements SanitizerInterface {
		/**
		 * Convert the supplied variable into a boolean value.
		 *
		 * @param mixed $input The input that will be sanitized to a boolean value.
		 * @throws \Exception
		 * @return mixed
		 */
		public function sanitize(mixed $input) : mixed {
			if (($value = json_decode($input, true)) === null) {
				// @codeCoverageIgnoreStart
				if (($error = json_last_error_msg()) === null) {
					$error = "Unable to decode the json.";
				}
				// @codeCoverageIgnoreEnd

				throw new \Exception($error);
			}

			return $value;
		}
	}
