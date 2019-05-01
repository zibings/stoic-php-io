<?php

	namespace Stoic\Utilities\Sanitizers;

	/**
	 * Class StringSanitizer
	 *
	 * @package Stoic\IO
	 * @version 1.0.0
	 */
	class StringSanitizer implements SanitizerInterface {

		/**
		 * Convert the supplied variable into a string value.
		 *
		 * @param mixed $input The input that will be sanitized to a string value.
		 *
		 * @throws \Exception
		 *
		 * @return string
		 */
		public function sanitize($input) {
			$value = '';

			try {
				if (is_object($input)) {
					if (method_exists($input, '__toString')) {
						$value = $input->__toString();
					}
				} else if (is_array($input)) {
					$value = serialize($input);

				} else if (is_bool($input)) {
					if ($input === true) {
						$value = 'true';

					} else {
						$value = 'false';
					}
				} else {
					$value = (string) $input;
				}
			// @codeCoverageIgnoreStart
			} catch (\Exception $ex) {
				throw $ex;
			}
			// @codeCoverageIgnoreEnd

			return $value;
		}
	}
