<?php

	namespace Stoic\Utilities;

	/**
	 * ParameterHelper to collect and serve parameters as typed values.
	 *
	 * @package Stoic\IO
	 * @version 1.1.0
	 */
	class ParameterHelper {
		/**
		 * Array of parameters.
		 *
		 * @var array
		 */
		protected array $parameters = [];
		/**
		 * Sanitation Helper
		 *
		 * @var null|SanitationHelper
		 */
		protected ?SanitationHelper $sanitizer = null;
		/**
		 * Source data fed to instance.
		 *
		 * @var array
		 */
		private array $source;


		/**
		 * Creates a new ParameterHelper instance.
		 *
		 * @param array $params Array of parameters to dispense.
		 * @param null|SanitationHelper $sanitizer Helper class that sanitizes values to a specific type.
		 */
		public function __construct(array $params = array(), ?SanitationHelper $sanitizer = null) {
			if (is_null($sanitizer)) {
				$sanitizer = new SanitationHelper();
			}

			$this->parameters = $params;
			$this->sanitizer  = $sanitizer;
			$this->source     = $params;

			return;
		}

		/**
		 * Returns the number of values in the parameter list.
		 *
		 * @return int
		 */
		public function count() : int {
			return count($this->parameters);
		}

		/**
		 * Returns a raw parameter value.
		 *
		 * @param null|string $key The name of the key/value pair we are looking for.
		 * @param mixed $default Optional Default value that is returned if key is not found.
		 * @param null|string $sanitizer The name of the sanitizer that will be used to cleanse the value.
		 * @return mixed
		 */
		public function get(?string $key, mixed $default = null, ?string $sanitizer = null) : mixed {
			if ($key === null) {
				return $this->parameters;
			}

			if (!$this->has($key)) {
				return $default;
			}

			if ($sanitizer !== null && $this->sanitizer->hasSanitizer($sanitizer)) {
				return $this->sanitizer->sanitize($this->parameters[$key], $sanitizer);
			}

			return $this->parameters[$key];
		}

		/**
		 * Returns a parameter cast as a bool.
		 *
		 * @param string $key The name of the key/value pair we are looking for.
		 * @param null|bool $default Optional Default value that is returned if key is not found.
		 * @return null|bool
		 */
		public function getBool(string $key, ?bool $default = null) : ?bool {
			if (!$this->has($key)) {
				return $default;
			}

			return $this->sanitizer->sanitize($this->parameters[$key], SanitationHelper::BOOLEAN);
		}

		/**
		 * Returns a parameter cast as a float.
		 *
		 * @param string $key The name of the key/value pair we are looking for.
		 * @param null|float $default Optional Default value that is returned if key is not found.
		 * @return null|float
		 */
		public function getFloat(string $key, ?float $default = null) : ?float {
			if (!$this->has($key)) {
				return $default;
			}

			return $this->sanitizer->sanitize($this->parameters[$key], SanitationHelper::FLOAT);
		}

		/**
		 * Returns a parameter cast as an integer.
		 *
		 * @param string $key The name of the key/value pair we are looking for.
		 * @param null|integer $default Optional Default value that is returned if key is not found.
		 * @return null|int
		 */
		public function getInt(string $key, ?int $default = null) : ?int {
			if (!$this->has($key)) {
				return $default;
			}

			return $this->sanitizer->sanitize($this->parameters[$key], SanitationHelper::INTEGER);
		}

		/**
		 * Returns a parameter cast as decoded JSON data.
		 *
		 * @param string $key The name of the key/value pair we are looking for.
		 * @param bool $asArray Force the json to be returned as an associative array.
		 * @param mixed $default Optional Default value that is returned if key is not found.
		 * @return mixed
		 */
		public function getJson(string $key, bool $asArray = false, mixed $default = null) : mixed {
			if (!$this->has($key)) {
				return $default;
			}

			if ( ($json = json_decode($this->parameters[$key], $asArray)) === null) {
				// @codeCoverageIgnoreStart
				return $default;
				// @codeCoverageIgnoreEnd
			}

			return $json;
		}

		/**
		 * Returns the source array the instance encapsulates.
		 *
		 * @return array
		 */
		public function getSource() : array {
			return $this->source;
		}

		/**
		 * Returns a parameter cast as a string.
		 *
		 * @param string $key The name of the key/value pair we are looking for.
		 * @param mixed $default Optional Default value that is returned if key is not found.
		 * @return null|string
		 */
		public function getString(string $key, mixed $default = null) : ?string {
			if (!$this->has($key)) {
				return $default;
			}

			return $this->sanitizer->sanitize($this->parameters[$key], SanitationHelper::STRING);
		}

		/**
		 * Check if a value exists within the parameter list.
		 *
		 * @param string $key The name of the key/value pair we are looking for.
		 * @return bool
		 */
		public function has(string $key) : bool {
			return array_key_exists($key, $this->parameters);
		}

		/**
		 * Checks if all provided keys exist within the parameter list.
		 *
		 * @param string[] $keys Collection of keys to find in parameter list.
		 * @return bool
		 */
		public function hasAll(string ...$keys) : bool {
			$ret = true;

			foreach (array_values($keys) as $key) {
				if (array_key_exists($key, $this->parameters) === false) {
					$ret = false;

					break;
				}
			}

			return $ret;
		}

		/**
		 * Return a clone of this ParameterHelper object with an added parameter.  If parameter is already present, value
		 * will be overwritten in clone.
		 *
		 * @param mixed $parameter Key for parameter to add (or change) in the cloned ParameterHelper.
		 * @param mixed $value Value for parameter in cloned ParameterHelper.
		 * @return ParameterHelper
		 */
		public function withParameter(mixed $parameter, mixed $value) : ParameterHelper {
			$new = clone $this;
			$new->parameters[$parameter] = $value;

			return $new;
		}

		/**
		 * Return a clone of this ParameterHelper object with additional parameters.  If any parameters are already
		 * present, their values will be overwritten.  Array format:
		 *
		 * [ 'key' => 'value' ]
		 *
		 * @param array $parameters Array of parameters to add (or change) in the cloned ParameterHelper.
		 * @return ParameterHelper
		 */
		public function withParameters(array $parameters) : ParameterHelper {
			$new = clone $this;

			foreach ($parameters as $param => $val) {
				$new->parameters[$param] = $val;
			}

			return $new;
		}

		/**
		 * Return a clone of this ParameterHelper object excluding the provided parameter if present.
		 *
		 * @param mixed $parameter Key for parameter to exclude in cloned ParameterHelper.
		 * @return ParameterHelper
		 */
		public function withoutParameter(mixed $parameter) : ParameterHelper {
			$new = clone $this;

			if (array_key_exists($parameter, $new->parameters) !== false) {
				unset($new->parameters[$parameter]);
			}

			return $new;
		}

		/**
		 * Return a clone of this ParameterHelper object excluding the provided parameters if present.  Array format:
		 *
		 * [ 'key1', 'key2' ]
		 *
		 * @param array $parameters Array of parameter keys to exclude in cloned ParameterHelper.
		 * @return ParameterHelper
		 */
		public function withoutParameters(array $parameters) : ParameterHelper {
			$new = clone $this;

			foreach ($parameters as $param) {
				if (array_key_exists($param, $new->parameters) !== false) {
					unset($new->parameters[$param]);
				}
			}

			return $new;
		}
	}
