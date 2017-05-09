<?php

	namespace Stoic\Sanitation\Tests;

	use PHPUnit\Framework\TestCase;
	use Stoic\Sanitation\SanitationHelper;
	use Stoic\Sanitation\SanitizerInterface;

	class StripArrayProperties implements SanitizerInterface {
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
		public function test_registerSanitizer() {
			SanitationHelper::registerSanitizer('strip_array', StripArrayProperties::class);

			$input = array(
				'class' => 'awesome',
				'style' => 'great',
				'attr'  => 'none'
			);

			$sanitation = SanitationHelper::getInstance();
			$output = $sanitation->sanitize($input, 'strip_array');

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
			$this->assertTrue($true);

			$this->assertFalse($zero);
			$this->assertTrue($one);

			$this->assertFalse($empty);
			$this->assertTrue($full);
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

			$this->assertEquals($object, 'test_string');
			$this->assertEquals($array, serialize(array()));
			$this->assertEquals($true, 'true');
			$this->assertEquals($false, 'false');
			$this->assertEquals($integer, '42');
			$this->assertEquals($float, '3.14');
			$this->assertEquals($string, 'actual_string');
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
			$this->assertEquals(0, $empty);
			$this->assertEquals(3, $full);
			$this->assertEquals(1, $true);
			$this->assertEquals(0, $false);
			$this->assertEquals(6, $string);
			$this->assertEquals(42, $integer);
			$this->assertEquals(3, $float);
			$this->assertEquals(42, $actualInt);
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
			$this->assertEquals(0, $empty);
			$this->assertEquals(3, $full);
			$this->assertEquals(1, $true);
			$this->assertEquals(0, $false);
			$this->assertEquals(6, $string);
			$this->assertEquals(42, $integer);
			$this->assertEquals(3.14, $float);
			$this->assertEquals(6.66, $actualFloat);
		}
	}
