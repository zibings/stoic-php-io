<?php

	namespace Stoic\IO;

	use Stoic\IO\SanitationHelper;

	/**
	 * Class ParameterHelper
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
		private $_parameters = array();

		/**
		 * Sanitation Helper
		 *
		 * @var SanitationHelper
		 */
		private $_sanitizer = null;

		/**
		 * Creates a new ParameterHelper instance.
		 *
		 * @param array            $params    Array of parameters to dispense.
		 * @param SanitationHelper $sanitizer Helper class that sanitizes values to a specific type.
		 */
		public function __construct(array $params = null, SanitationHelper $sanitizer = null) {
			if (is_null($params)) {
				$params = array();
			}

			if (is_null($sanitizer)) {
				$sanitizer = new SanitationHelper();
			}

			$this->_parameters = $params;
			$this->_sanitizer  = $sanitizer;
		}

		/**
		 * Returns the number of values in the parameter list.
		 *
		 * @return int Number of parameters.
		 */
		public function count() {
			return count($this->_parameters);
		}

		/**
		 * Check if a value exists within the parameter list.
		 *
		 * @param string $key The name of the key/value pair we are looking for.
		 *
		 * @return bool True if key exists in parameter list, false otherwise.
		 */
		public function has($key) {
			return array_key_exists($key, $this->_parameters);
		}

		/**
		 * Returns a parameter cast as an integer.
		 *
		 * @param string       $key     The name of the key/value pair we are looking for.
		 * @param integer|null $default Optional Default value that is returned if key is not found.
		 *
		 * @return int Integer value of key or default value if not present.
		 */
		public function getInt($key, $default = null) {
			if (!$this->has($key)) {
				return $default;
			}

			return $this->_sanitizer->sanitize($this->_parameters[$key], SanitationHelper::INTEGER);
		}

		/**
		 * Returns a parameter cast as a float.
		 *
		 * @param string     $key     The name of the key/value pair we are looking for.
		 * @param float|null $default Optional Default value that is returned if key is not found.
		 *
		 * @return float Float value of key or default value if not present.
		 */
		public function getFloat($key, $default = null) {
			if (!$this->has($key)) {
				return $default;
			}

			return $this->_sanitizer->sanitize($this->_parameters[$key], SanitationHelper::FLOAT);
		}

		/**
		 * Returns a parameter cast as a bool.
		 *
		 * @param string    $key     The name of the key/value pair we are looking for.
		 * @param bool|null $default Optional Default value that is returned if key is not found.
		 *
		 * @return bool Bool value of key or default value if not present.
		 */
		public function getBool($key, $default = null) {
			if (!$this->has($key)) {
				return $default;
			}

			return $this->_sanitizer->sanitize($this->_parameters[$key], SanitationHelper::BOOLEAN);
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
		public function getJson($key, $asArray = false, $default = null) {
			if (!$this->has($key)) {
				return $default;
			}

			if ( ($json = json_decode($key, $asArray)) === null) {
				return $default;
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
		public function getString($key, $default = null) {
			if (!$this->has($key)) {
				return $default;
			}

			return $this->_sanitizer->sanitize($this->_parameters[$key], SanitationHelper::STRING);
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
		public function get($key, $default = null, $sanitizer = null) {
			if ($key === null) {
				return $this->_parameters;
			}

			if (!$this->has($key)) {
				return $default;
			}

			if ($sanitizer !== null && $this->_sanitizer->hasSanitizer($sanitizer)) {
				return $this->_sanitizer->sanitize($this->_parameters[$key], $sanitizer);
			}

			return $this->_parameters[$key];
		}

		/**
		 * Sets a new key/pair value.
		 *
		 * @param string $key   The name of the value we are setting.
		 * @param mixed  $value The value that we are setting.
		 *
		 * @return $this
		 */
		public function add($key, $value) {
			if (!is_string($key) && !is_int($key)) {
				return $this;
			}

			$this->_parameters[$key] = $value;

			return $this;
		}

		/**
		 * Adds multiple key/value pairs to the parameters array.
		 *
		 * @param array $params
		 *
		 * @return $this
		 */
		public function addValues(array $params) {
			foreach ($params as $key => $value) {
				$this->add($key, $value);
			}

			return $this;
		}

		/**
		 * Clears all the values from the parameter array.
		 *
		 * @return void
		 */
		public function clear() {
			$this->_parameters = array();
		}
	}
