## ParameterHelper Class
The `ParameterHelper` class provides some structure to dealing with array-based
parameter sets, such as those from super globals like `$_GET` or `$_POST`.

### Methods
- [public] `__construct($params, $sanitizer)` -> Instantiates a new `ParameterHelper` object
- [public] `count()` -> Returns the number of parameters in the parameter stack
- [public] `get($key, $default, $sanitizer)` -> Attempts to retrieve a parameter with optional sanitizer selection
- [public] `getBool($key, $default)` -> Attempts to retrieve a parameter as a boolean value
- [public] `getFloat($key, $default)` -> Attempts to retrieve a parameter as a float value
- [public] `getInt($key, $default)` -> Attempts to retrieve a parameter as an integer value
- [public] `getJson($key, $asArray, $default)` -> Attempts to retrieve a parameter as a decoded JSON object/array
- [public] `getString($key, $default)` -> Attempts to retrieve a parameter as a string avlue
- [public] `has($key)` -> Check if a parameter is contained in the parameter stack
- [public] `withParameter($parameter, $value)` -> Returns a clone with the added or changed parameter provided
- [public] `withParameters($parameters)` -> Returns a clone with the added or changed parameters provided
- [public] `withoutParameter($parameter)` -> Returns a clone with the provided parameter excluded
- [public] `withoutParameters($parameters)` -> Returns a clone with the provided parameters excluded