<?php

	namespace Stoic\Input\Sanitizers;

	/**
	 * Interface SanitizerInterface
	 *
	 * @package Stoic\Input
	 * @version 1.0.0
	 */
	interface SanitizerInterface {

		/**
		 * Converts the supplied variable into a specific variable type.
		 *
		 * @param mixed $input The variable that will be converted.
		 *
		 * @return mixed
		 */
		public function sanitize($input);
	}