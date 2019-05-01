## Sanitation Helper
Lets face it, the things that users input into our system are sometimes dirty.
It's our job to clean it up before they infect our system with something nasty.
Which is why we have the sanitation helper. This handy little helper will convert
your users input into the format that you are expecting.

The `Stoic\Input\SanitationHelper` comes with four sanitizers by default, `bool`,
`int`, `float` and `string`. You can access those sanitizer by calling the
`SanitationHelper::sanitize($input, $sanitizer)` method, where `$input` is
the variable that you want to sanitize, and `$sanitizer` is the name of the
sanitizer you want to use.

```php
<?php
 
use Stoic\Input\SanitationHelper;
 
// Initialize the sanitation helper.
$sanitation = new SanitationHelper();
 
// Retrieve a boolean value using the constant
$boolVal = $sanitation->sanitize('true', SanitationHelper::BOOLEAN);
 
// Retrieve an integer value
$intVal = $sanitation->sanitize('123', SanitationHelper::INTEGER);
 
// Retrieve a floating point value
$floatVal = $sanitation->sanitize('3.14', SanitationHelper::FLOAT);
 
// Retrieve a string value
$stringVal = $sanitation->sanitize(42, SanitationHelper::STRING);

```

### Creating a New Sanitizer
So, you want to sanitize your data in a way that we don't have available yet. Perhaps
you want to turn a value into a neatly formatted phone number or DateTime object. You'll
need to create your own sanitizer in order to do that.

The `SanitationHelper` only accepts sanitizers that implement the `SanitizerInterface`. That
interface only has one method that you'll need to implement called `sanitize($input)`. That
method is where the sanitation magic happens.

```php
<?php
 
namespace Foo\Bar;
 
use Stoic\Input\Sanitizers\SanitizerInterface;
 
class DateTimeSanitizer implements SanitizerInterface {
     
     /**
      * This sanitizer will take the input and transform it into a DateTime object.
      *
      * @param mixed $input The variable that will be transformed into a DateTime object.
      *
      * @throws \Exception
      *
      * @return \DateTime
      */
     public function sanitize($input) {
          try {
               $value = new \DateTime($input);
			
          } catch (\Exception $ex) {
               throw $ex;
          }
		
          return $value;
    }
}

```

### Adding a New Sanitizer
So, you've just created your first new sanitizer and you want to start using it.
In order to do that, you'll need to register it with the sanitizer helper that
you want to use it with. There are two ways you can add a new sanitizer, the first
is to pass the fully qualified namespace.

```php
<?php
 
use Stoic\Input\SanitationHelper;
use Foo\Bar\DateTimeSanitizer;
 
$sanitation = new SanitationHelper();
$sanitation->addSanitizer('datetime', DateTimeSanitizer::class);

```

When passing the fully qualified namespace above, our sanitation helper will
instantiate the object auto-magically. The second way is to pass in an already
instantiated sanitizer object.

```php
<?php
 
use Stoic\Input\SanitationHelper;
use Foo\Bar\DateTimeSanitizer;
 
$sanitation = new SanitationHelper();
$sanitation->addSanitizer('datetime', new DateTimeSanitizer());

```