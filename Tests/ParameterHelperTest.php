<?php

	namespace Stoic\Tests\Utilities;

	use PHPUnit\Framework\TestCase;
	use Stoic\Utilities\ParameterHelper;

	class ParameterHelperTest extends TestCase {
		protected $_params = array(
			'string'  => 'Awesome',
			'integer' => 42,
			'float'   => 3.14,
			'bool'    => true
		);

		protected $_additional = array(
			'name'   => 'Chris',
			'age'    => 32,
			'alive'  => true,
			'height' => 175.26
		);

		public function test_numValues() {
			$ph = new ParameterHelper($this->_params);

			$this->assertEquals(4, $ph->count());
		}

		public function test_hasValue() {
			$ph = new ParameterHelper($this->_params);

			$this->assertTrue($ph->has('string'));
			$this->assertFalse($ph->has('non-existent'));
		}

		public function test_getDefaultValues() {
			$ph = new ParameterHelper();

			$this->assertEquals('Chris', $ph->get('non-existent', 'Chris'));
			$this->assertEquals(123, $ph->getInt('non-existent', 123));
			$this->assertEquals(true, $ph->getBool('non-existent', true));
			$this->assertEquals('Test', $ph->getString('non-existent', 'Test'));
			$this->assertEquals(18.5, $ph->getFloat('non-existent', 18.5));
		}

		public function test_GetValues() {
			$ph = new ParameterHelper(array_merge($this->_params, [
				'json' => json_encode(['testing' => 'values'])
			]));

			self::assertEquals('Awesome', $ph->getString('string'));
			self::assertEquals(42, $ph->getInt('integer'));
			self::assertEquals(3.14, $ph->getFloat('float'));
			self::assertEquals(true, $ph->getBool('bool'));
			self::assertEquals("Array\n(\n    [testing] => values\n)\n", print_r($ph->getJson('json', true), true));
			self::assertNull($ph->getJson('notthere'));
			self::assertEquals("Array\n(\n    [string] => Awesome\n    [integer] => 42\n    [float] => 3.14\n    [bool] => 1\n    [json] => {\"testing\":\"values\"}\n)\n", print_r($ph->get(null), true));
			self::assertEquals(42, $ph->get('integer', null, 'int'));

			return;
		}
	}
