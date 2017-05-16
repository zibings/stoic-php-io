<?php

	namespace Stoic\IO\Tests;

	use PHPUnit\Framework\TestCase;
	use Stoic\IO\ReturnHelper;

	class ReturnHelperTest extends TestCase {
		public function test_isGoodAtInitialization() {
			$ret = new ReturnHelper();
			$this->assertTrue($ret->isGood());
		}

		public function test_nullMessagesAtInitialization() {
			$ret = new ReturnHelper();
			$this->assertEquals(null, $ret->getMessages());
		}

		public function test_nullResultsAtInitialization() {
			$ret = new ReturnHelper();
			$this->assertEquals(null, $ret->getMessages());
		}

		public function test_canBeMadeToBadAndGood() {
			$ret = new ReturnHelper();
			$ret->makeBad();

			$this->assertTrue($ret->isBad());
			$this->assertFalse($ret->isGood());

			$ret->makeGood();

			$this->assertTrue($ret->isGood());
			$this->assertFalse($ret->isBad());
		}

		public function test_addMessage() {
			$ret = new ReturnHelper();
			$ret ->addMessage("Test message one.");

			$messages = $ret->getMessages();

			$this->assertCount(1, $messages);
			$this->assertEquals("Test message one.", $messages[0]);
		}

		public function test_addMultipleMessage() {
			$ret = new ReturnHelper();
			$ret ->addMessage("Test message one.")
			     ->addMessage("Test message two.")
			     ->addMessage("Test message three.");

			$messages = $ret->getMessages();

			$this->assertCount(3, $messages);
			$this->assertEquals("Test message one.", $messages[0]);
			$this->assertEquals("Test message three.", $messages[2]);
		}

		public function test_addMessages() {
			$ret = new ReturnHelper();
			$ret->addMessages(array(
				"Test message one.",
				"Test message two.",
				"Test message three.",
			));

			$messages = $ret->getMessages();

			$this->assertCount(3, $messages);
			$this->assertEquals("Test message one.", $messages[0]);
			$this->assertEquals("Test message three.", $messages[2]);
		}

		public function test_addResult() {
			$ret = new ReturnHelper();
			$ret->addResult("One");

			$result = $ret->getResults();

			$this->assertEquals($result, "One");
		}

		public function test_addMultipleResult() {
			$ret = new ReturnHelper();
			$ret ->addResult("One")
			     ->addResult("Two")
			     ->addResult("Three");

			$results = $ret->getResults();

			$this->assertCount(3, $results);
			$this->assertEquals("One", $results[0]);
			$this->assertEquals("Three", $results[2]);
		}

		public function test_addResults() {
			$ret = new ReturnHelper();
			$ret ->addResults(array(
				"One",
				"Two",
				"Three"
			));

			$results = $ret->getResults();

			$this->assertCount(3, $results);
			$this->assertEquals("One", $results[0]);
			$this->assertEquals("Three", $results[2]);
		}
	}
