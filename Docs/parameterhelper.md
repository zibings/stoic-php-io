## ParameterHelper Class
The `ParameterHelper` class provides some structure to dealing with array-based
parameter sets, such as those from super globals like `$_GET` or `$_POST`.

### Methods
- [public] `__construct($params, $sanitizer)` -> Instantiates a new `ParameterHelper` object
- [public] `count()` -> Returns the number of parameters in the parameter stack
- [public] `has($key)` -> Check if a parameter is contained in the parameter stack
- [public] `getInt($key, $default)` -> Attempts to retrieve a parameter as an integer value
- [public] `getFloat($key, $default)` -> Attempts to retrieve a parameter as a float value
- [public] `getBool($key, $default)` -> Attempts to retrieve a parameter as a boolean value
- [public] `getJson($key, $asArray, $default)` -> Attempts to retrieve a parameter as a decoded JSON object/array
- [public] `getString($key, $default)` -> Attempts to retrieve a parameter as a string avlue
- [public] `get($key, $default, $sanitizer)` -> Attempts to retrieve a parameter with optional sanitizer selection
- [public] `add($key, $value)` -> Adds a value to the parameter stack
- [public] `addValues($params)` -> Adds a collection of values to the parameter stack
- [public] `remove($key)` -> Attempts to remove a parameter from the parameter stack
- [public] `clear()` -> Clears all parameters from the parameter stack