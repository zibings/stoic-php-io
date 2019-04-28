<?php

	namespace Stoic\Tests\Utilities;

	use PHPUnit\Framework\TestCase;
	use Stoic\Utilities\ParameterHelper;

	class ParameterHelperTest extends TestCase {
		protected $_params = array(
			'string'  => 'Awesome',
			'integer' => 42,
			'float'   => 3.14,
			'bool'    => true,
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

		public function test_addValue() {
			$ph = new ParameterHelper($this->_params);
			$ph->add('name', 'Chris');

			$this->assertTrue($ph->has('name'));
			$this->assertEquals('Chris', $ph->get('name'));
		}

		public function test_addValues() {
			$ph = new ParameterHelper();
			$ph->addValues($this->_additional);

			$this->assertTrue($ph->has('age'));
			$this->assertEquals(4, $ph->count());
		}

		public function test_removeValue() {
			$ph = new ParameterHelper();
			$ph->add('name', 'Test');

			$this->assertTrue($ph->has('name'));

			$ph->remove('name');

			$this->assertFalse($ph->has('name'));
		}
	}
