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
			if (!is_array($params)) {
				return;
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
		public function numValues() {
			return count($this->_parameters);
		}

		/**
		 * Check if a value exists within the parameter list.
		 *
		 * @param string $key The name of the key/value pair we are looking for.
		 *
		 * @return bool True if key exists in parameter list, false otherwise.
		 */
		public function hasValue($key) {
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
			if (!$this->hasValue($key)) {
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
			if (!$this->hasValue($key)) {
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
			if (!$this->hasValue($key)) {
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
			if (!$this->hasValue($key)) {
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
			if (!$this->hasValue($key)) {
				return $default;
			}

			return $this->_sanitizer->sanitize($this->_parameters[$key], SanitationHelper::STRING);
		}

		/**
		 * Returns a raw parameter value.
		 *
		 * @param string $key     The name of the key/value pair we are looking for.
		 * @param mixed  $default Optional Default value that is returned if key is not found.
		 *
		 * @return mixed Mixed The value of the key if found or default value if not present.
		 */
		public function get($key, $default = null) {
			if ($key === null) {
				return $this->_parameters;
			}

			if (!$this->hasValue($key)) {
				return $default;
			}

			return $this->_parameters[$key];
		}
	}
