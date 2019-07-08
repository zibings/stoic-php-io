<?php

	namespace Stoic\Utilities;

	/**
	 * Mutable ParameterHelper class for groups of parameters
	 * that might change.
	 *
	 * @package Stoic\IO
	 * @version 1.0.0
	 */
	class MutableParameterHelper extends ParameterHelper {
		/**
		 * Sets a new key/pair value.
		 *
		 * @param string $key   The name of the value we are setting.
		 * @param mixed  $value The value that we are setting.
		 *
		 * @return $this
		 */
		public function add(string $key, $value) : MutableParameterHelper {
			if (!is_string($key) && !is_int($key)) {
				return $this;
			}

			$this->parameters[$key] = $value;

			return $this;
		}

		/**
		 * Adds multiple key/value pairs to the parameters array.
		 *
		 * @param array $params
		 *
		 * @return $this
		 */
		public function addValues(array $params) : MutableParameterHelper {
			foreach ($params as $key => $value) {
				$this->add($key, $value);
			}

			return $this;
		}

		/**
		 * Remove a key/value pair from the parameters.
		 *
		 * @param string $key The name of the value we are removing.
		 *
		 * @return $this
		 */
		public function remove(string $key) : MutableParameterHelper {
			if ($this->has($key)) {
				unset($this->parameters[$key]);
			}

			return $this;
		}

		/**
		 * Clears all the values from the parameter array.
		 *
		 * @return $this
		 */
		public function clear() : MutableParameterHelper {
			$this->parameters = [];

			return $this;
		}
	}
