<?php

	namespace Stoic\IO;

	/**
	 * Class ReturnHelper
	 *
	 * @package Stoic\IO
	 * @version 1.0.0
	 */
	class ReturnHelper {

		/**
		 * Collection of messages for instance.
		 *
		 * @var array
		 */
		private $_messages;

		/**
		 * Collection of results for instance.
		 *
		 * @var array
		 */
		private $_results;

		/**
		 * Current status for instance. Default is GOOD.
		 *
		 * @var int
		 */
		private $_status;

		const BAD = 0;
		const GOOD = 1;

		/**
		 * ReturnHelper constructor.
		 */
		public function __construct() {
			$this->_messages = array();
			$this->_results  = array();
			$this->_status   = self::GOOD;
		}

		/**
		 * Returns whether or not the status is bad.
		 *
		 * @return bool True if status is BAD, false otherwise.
		 */
		public function isBad() {
			return !$this->_status;
		}

		/**
		 * Returns whether or not the status is good.
		 *
		 * @return bool True if status is GOOD, false otherwise.
		 */
		public function isGood() {
			return $this->_status === self::GOOD;
		}

		/**
		 * Returns any messages the instance contains.
		 *
		 * @return array|null Array of messages if available, null otherwise.
		 */
		public function getMessages() {
			if (count($this->_messages) < 1) {
				return null;
			}

			return $this->_messages;
		}

		/**
		 * Returns any results the instance contains.
		 *
		 * @return mixed|null Results if available, null otherwise.
		 */
		public function getResults() {
			if (count($this->_results) < 1) {
				return null;
			} else if (count($this->_results) == 1) {
				return $this->_results[0];
			}

			return $this->_results;
		}

		/**
		 * Returns whether or not the instance contains
		 * messages.
		 *
		 * @return bool True if messages are present, false otherwise.
		 */
		public function hasMessage() {
			return count($this->_messages) > 0;
		}

		/**
		 * Adds a message to the instance.
		 *
		 * @param string $Message String value of message to add.
		 *
		 * @return $this The current ReturnHelper instance.
		 */
		public function addMessage($Message) {
			$this->_messages[] = $Message;

			return $this;
		}

		/**
		 * Adds multiple messages to the instance.
		 *
		 * @param array $Messages Array of string values to add.
		 *
		 * @return $this The current ReturnHelper instance.
		 */
		public function addMessages(array $Messages) {
			if (count($Messages) < 1) {
				return $this;
			}

			foreach (array_values($Messages) as $Msg) {
				$this->_messages[] = $Msg;
			}

			return $this;
		}

		/**
		 * Adds a result to the instance.
		 *
		 * @param mixed $Result Result to add to instance.
		 *
		 * @return $this The current ReturnHelper instance.
		 */
		public function addResult($Result) {
			$this->_results[] = $Result;

			return $this;
		}

		/**
		 * Adds multiple results to the instance.
		 *
		 * @param array $Results Array of results to add.
		 *
		 * @return $this The current ReturnHelper instance.
		 */
		public function addResults(array $Results) {
			if (count($Results) < 1) {
				return $this;
			}

			foreach (array_values($Results) as $Res) {
				$this->_results[] = $Res;
			}

			return $this;
		}

		/**
		 * Sets the instance status to BAD.
		 *
		 * @return $this The current ReturnHelper instance.
		 */
		public function makeBad() {
			$this->_status = self::BAD;

			return $this;
		}

		/**
		 * Sets the instance status to GOOD.
		 *
		 * @return $this The current ReturnHelper instance.
		 */
		public function makeGood() {
			$this->_status = self::GOOD;

			return $this;
		}

		/**
		 * Sets the instance status.
		 *
		 * @param int $Status Integer value of new instance status.
		 *
		 * @return $this The current ReturnHelper instance.
		 */
		public function setStatus($Status) {
			$this->_status = intval($Status);

			return $this;
		}
	}
