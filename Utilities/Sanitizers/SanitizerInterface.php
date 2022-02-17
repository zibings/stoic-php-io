<?php

	namespace Stoic\Utilities\Sanitizers;

	/**
	 * Interface SanitizerInterface
	 *
	 * @package Stoic\IO
	 * @version 1.1.0
	 */
	interface SanitizerInterface {
		/**
		 * Converts the supplied variable into a specific variable type.
		 *
		 * @param mixed $input The variable that will be converted.
		 * @return mixed
		 */
		public function sanitize(mixed $input) : mixed;
	}
