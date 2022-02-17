<?php

	namespace Stoic\Utilities;

	use Stoic\Utilities\Sanitizers\SanitizerInterface;
	use Stoic\Utilities\Sanitizers\BooleanSanitizer;
	use Stoic\Utilities\Sanitizers\IntegerSanitizer;
	use Stoic\Utilities\Sanitizers\FloatSanitizer;
	use Stoic\Utilities\Sanitizers\StringSanitizer;

	/**
	 * Class SanitationHelper
	 *
	 * @package Stoic\IO
	 * @version 1.1.0
	 */
	class SanitationHelper {
		const BOOLEAN = 'bool';
		const INTEGER = 'int';
		const FLOAT   = 'float';
		const STRING  = 'string';


		/**
		 * List of all the instantiated sanitizers.
		 *
		 * @var SanitizerInterface[]
		 */
		protected array $_sanitizers = [];


		/**
		 * List of all the default sanitizer classes.
		 *
		 * @var string[]
		 */
		protected static array $_defaults = [
			self::BOOLEAN => BooleanSanitizer::class,
			self::INTEGER => IntegerSanitizer::class,
			self::FLOAT   => FloatSanitizer::class,
			self::STRING  => StringSanitizer::class,
		];


		/**
		 * Instantiates a new SanitationHelper object, adding all default sanitizers.
		 */
		public function __construct() {
			foreach (static::$_defaults as $name => $class) {
				$this->addSanitizer($name, $class);
			}

			return;
		}

		/**
		 * Add a new sanitation type.
		 *
		 * @param string $key The key, which is a string value, that described the sanitizer being added.
		 * @param string|object $sanitizer The instance of the sanitizer class, or a fully qualified domain name of the class.
		 * @return SanitationHelper
		 */
		public function addSanitizer(string $key, string|object $sanitizer) : SanitationHelper {
			if ($sanitizer instanceof SanitizerInterface) {
				$this->_sanitizers[$key]= $sanitizer;
			} else if (is_string($sanitizer) && class_exists($sanitizer) && class_implements($sanitizer, SanitizerInterface::class)) {
				$this->_sanitizers[$key] = new $sanitizer();
			}

			return $this;
		}

		/**
		 * Check whether a sanitation type exists.
		 *
		 * @param string $key The key, which is a string value, of the sanitizer that is being searched for.
		 * @return bool
		 */
		public function hasSanitizer(string $key) : bool {
			return isset($this->_sanitizers[$key]);
		}

		/**
		 * Converts the supplied input to a specific value.
		 *
		 * @param mixed $input The value that is going to be sanitized.
		 * @param string $key The key, which is a string value, for the sanitizer that will be used.
		 * @return mixed
		 */
		public function sanitize(mixed $input, string $key) : mixed {
			if (!$this->hasSanitizer($key)) {
				return $input;
			}

			return $this->_sanitizers[$key]->sanitize($input);
		}
	}
