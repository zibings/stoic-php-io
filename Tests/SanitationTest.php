<?php

	namespace Stoic\Input\Tests;

	use PHPUnit\Framework\TestCase;
	use Stoic\Input\SanitationHelper;
	use Stoic\Input\Sanitizers\SanitizerInterface;

	class StripArrayProperties implements SanitizerInterface {
		const name = 'strip_array_properties';

		protected $_safe = array(
			'class',
			'style',
		);

		public function sanitize($input) {
			$array = array();

			if (is_array($input)) {
				foreach ($input as $name => $value) {
					if (in_array($name, $this->_safe)) {
						$array[$name] = $value;
					}
				}
			}

			return $array;
		}
	}

	class testObject {
		public $one;
		public $two;
		protected $three;

		public function __toString() {
			return 'test_string';
		}
	}

	class SanitationTest extends TestCase {
		public function test_registerNewSanitizer() {
			SanitationHelper::registerSanitizer(StripArrayProperties::name, StripArrayProperties::class);

			$input = array(
				'class' => 'awesome',
				'style' => 'great',
				'attr'  => 'none'
			);

			$sanitation = SanitationHelper::getInstance();
			$output = $sanitation->sanitize($input, StripArrayProperties::name);

			$this->assertCount(2, $output);
			$this->assertArrayHasKey('class', $output);
			$this->assertArrayHasKey('style', $output);
			$this->assertArrayNotHasKey('attr', $output);
		}

		public function test_booleanSanitizer() {
			$sanitation = SanitationHelper::getInstance();

			$false = $sanitation->boolean(false);
			$true  = $sanitation->boolean(true);
			$zero  = $sanitation->boolean(0);
			$one   = $sanitation->boolean(1);
			$empty = $sanitation->boolean(array());
			$full  = $sanitation->boolean(array('one', 'two'));

			$this->assertFalse($false);
			$this->assertTrue(is_bool($false));

			$this->assertTrue($true);
			$this->assertTrue(is_bool($true));

			$this->assertFalse($zero);
			$this->assertTrue(is_bool($zero));

			$this->assertTrue($one);
			$this->assertTrue(is_bool($one));

			$this->assertFalse($empty);
			$this->assertTrue(is_bool($empty));

			$this->assertTrue($full);
			$this->assertTrue(is_bool($full));
		}

		public function test_stringSanitizer() {
			$sanitation = SanitationHelper::getInstance();

			$object  = $sanitation->string(new testObject());
			$array   = $sanitation->string(array());
			$true    = $sanitation->string(true);
			$false   = $sanitation->string(false);
			$integer = $sanitation->string(42);
			$float   = $sanitation->string(3.14);
			$string  = $sanitation->string('actual_string');

			$this->assertEquals('test_string', $object);
			$this->assertTrue(is_string($object));

			$this->assertEquals(serialize(array()), $array);
			$this->assertTrue(is_string($array));

			$this->assertEquals('true', $true);
			$this->assertTrue(is_string($true));

			$this->assertEquals('false', $false);
			$this->assertTrue(is_string($false));

			$this->assertEquals('42', $integer);
			$this->assertTrue(is_string($integer));

			$this->assertEquals('3.14', $float);
			$this->assertTrue(is_string($float));

			$this->assertEquals('actual_string', $string);
			$this->assertTrue(is_string($string));
		}

		public function test_integerSanitizer() {
			$sanitation = SanitationHelper::getInstance();

			$object    = $sanitation->integer(new testObject());
			$empty     = $sanitation->integer(array());
			$full      = $sanitation->integer(array(1,2,3));
			$true      = $sanitation->integer(true);
			$false     = $sanitation->integer(false);
			$string    = $sanitation->integer('string');
			$integer   = $sanitation->integer('42');
			$float     = $sanitation->integer('3.14');
			$actualInt = $sanitation->integer(42);

			$this->assertEquals(2, $object);
			$this->assertTrue(is_int($object));

			$this->assertEquals(0, $empty);
			$this->assertTrue(is_int($empty));

			$this->assertEquals(3, $full);
			$this->assertTrue(is_int($full));

			$this->assertEquals(1, $true);
			$this->assertTrue(is_int($true));

			$this->assertEquals(0, $false);
			$this->assertTrue(is_int($false));

			$this->assertEquals(6, $string);
			$this->assertTrue(is_int($string));

			$this->assertEquals(42, $integer);
			$this->assertTrue(is_int($integer));

			$this->assertEquals(3, $float);
			$this->assertTrue(is_int($float));

			$this->assertEquals(42, $actualInt);
			$this->assertTrue(is_int($actualInt));
		}

		public function test_floatSanitizer() {
			$sanitation = SanitationHelper::getInstance();

			$object      = $sanitation->float(new testObject());
			$empty       = $sanitation->float(array());
			$full        = $sanitation->float(array(1,2,3));
			$true        = $sanitation->float(true);
			$false       = $sanitation->float(false);
			$string      = $sanitation->float('string');
			$integer     = $sanitation->float('42');
			$float       = $sanitation->float('3.14');
			$actualFloat = $sanitation->float(6.66);

			$this->assertEquals(2, $object);
			$this->assertTrue(is_float($object));

			$this->assertEquals(0, $empty);
			$this->assertTrue(is_float($empty));

			$this->assertEquals(3, $full);
			$this->assertTrue(is_float($full));

			$this->assertEquals(1, $true);
			$this->assertTrue(is_float($true));

			$this->assertEquals(0, $false);
			$this->assertTrue(is_float($false));

			$this->assertEquals(6, $string);
			$this->assertTrue(is_float($string));

			$this->assertEquals(42, $integer);
			$this->assertTrue(is_float($integer));

			$this->assertEquals(3.14, $float);
			$this->assertTrue(is_float($float));

			$this->assertEquals(6.66, $actualFloat);
			$this->assertTrue(is_float($actualFloat));
		}
	}
