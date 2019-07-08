<?php

	namespace Stoic\Utilities;

	use Stoic\Utilities\SanitationHelper;

	/**
	 * ParameterHelper to collect and serve parameters as typed values.
	 *
	 * @package Stoic\IO
	 * @version 1.0.0
	 */
	class ParameterHelper {
		/**
		 * Array of parameters.
		 *
		 * @var array
		 */
		protected $parameters = array();
		/**
		 * Sanitation Helper
		 *
		 * @var SanitationHelper
		 */
		protected $sanitizer = null;


		/**
		 * Creates a new ParameterHelper instance.
		 *
		 * @param array            $params    Array of parameters to dispense.
		 * @param SanitationHelper $sanitizer Helper class that sanitizes values to a specific type.
		 */
		public function __construct(array $params = array(), SanitationHelper $sanitizer = null) {
			if (is_null($sanitizer)) {
				$sanitizer = new SanitationHelper();
			}

			$this->parameters = $params;
			$this->sanitizer  = $sanitizer;

			return;
		}

		/**
		 * Returns the number of values in the parameter list.
		 *
		 * @return int Number of parameters.
		 */
		public function count() : int {
			return count($this->parameters);
		}

		/**
		 * Check if a value exists within the parameter list.
		 *
		 * @param string $key The name of the key/value pair we are looking for.
		 *
		 * @return bool True if key exists in parameter list, false otherwise.
		 */
		public function has(string $key) : bool {
			return array_key_exists($key, $this->parameters);
		}

		/**
		 * Returns a parameter cast as an integer.
		 *
		 * @param string       $key     The name of the key/value pair we are looking for.
		 * @param integer|null $default Optional Default value that is returned if key is not found.
		 *
		 * @return int Integer value of key or default value if not present.
		 */
		public function getInt(string $key, $default = null) : int {
			if (!$this->has($key)) {
				return $default;
			}

			return $this->sanitizer->sanitize($this->parameters[$key], SanitationHelper::INTEGER);
		}

		/**
		 * Returns a parameter cast as a float.
		 *
		 * @param string     $key     The name of the key/value pair we are looking for.
		 * @param float|null $default Optional Default value that is returned if key is not found.
		 *
		 * @return float Float value of key or default value if not present.
		 */
		public function getFloat(string $key, $default = null) : float {
			if (!$this->has($key)) {
				return $default;
			}

			return $this->sanitizer->sanitize($this->parameters[$key], SanitationHelper::FLOAT);
		}

		/**
		 * Returns a parameter cast as a bool.
		 *
		 * @param string    $key     The name of the key/value pair we are looking for.
		 * @param bool|null $default Optional Default value that is returned if key is not found.
		 *
		 * @return bool Bool value of key or default value if not present.
		 */
		public function getBool(string $key, $default = null) : bool {
			if (!$this->has($key)) {
				return $default;
			}

			return $this->sanitizer->sanitize($this->parameters[$key], SanitationHelper::BOOLEAN);
		}

		/**
		 * Returns a parameter cast as decoded JSON data.
		 *
		 * @param string $key     The name of the key/value pair we are looking for.
		 * @param bool   $asArray Force the json to be returned as an associative array.
		 * @param mixed  $default Optional Default value that is returned if key is not found.
		 *
		 * @return mixed Mixed value of key or default value if not present.
		 */
		public function getJson(string $key, $asArray = false, $default = null) {
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
		 * Returns a parameter cast as a string.
		 *
		 * @param string $key     The name of the key/value pair we are looking for.
		 * @param mixed  $default Optional Default value that is returned if key is not found.
		 *
		 * @return string String value of key or default value if not present.
		 */
		public function getString(string $key, $default = null) : string {
			if (!$this->has($key)) {
				return $default;
			}

			return $this->sanitizer->sanitize($this->parameters[$key], SanitationHelper::STRING);
		}

		/**
		 * Returns a raw parameter value.
		 *
		 * @param string $key       The name of the key/value pair we are looking for.
		 * @param mixed  $default   Optional Default value that is returned if key is not found.
		 * @param string $sanitizer The name of the sanitizer that will be used to cleanse the value.
		 *
		 * @return mixed Mixed The value of the key if found or default value if not present.
		 */
		public function get(string $key, $default = null, string $sanitizer = null) {
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
	}
