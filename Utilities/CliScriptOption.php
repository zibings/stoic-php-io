<?php

	namespace Stoic\Utilities;

	/**
	 * Structure for defining the components of a script option.
	 *
	 * @package Zibings
	 */
	class CliScriptOption {
		/**
		 * CliScriptOption constructor.
		 *
		 * @param mixed $defaultValue Default value of the option, if not provided.
		 * @param string $longDescription Longer description, displayed on multiple lines.
		 * @param string $longName Long name/argument for option.
		 * @param string $name Identifier for option.
		 * @param bool $required Whether the option is required.
		 * @param string $shortDescription Short, one-line description for option.
		 * @param string $shortName Short name/argument for option.
		 * @return void
		 */
		public function __construct(
			public mixed  $defaultValue,
			public string $longDescription,
			public string $longName,
			public string $name,
			public bool   $required,
			public string $shortDescription,
			public string $shortName) {
			return;
		}
	}
