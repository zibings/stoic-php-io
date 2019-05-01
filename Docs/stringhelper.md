## StringHelper Class
The `StringHelper` class provides a wrapper around PHP strings that generalizes
common operations such as concatenation, searching, and comparisons.

#### Static Methods
- [public] `join()` -> Joins strings together, optionally accepts a `StringJoinOptions` object as the first argument

#### Methods
- [public] `__construct($source)` -> Instantiates a new StringHelper object
- [public] `append($string)` -> Appends a string onto the internal data store
- [public] `at($position)` -> Retrieves the character at the given position
- [public] `clear()` -> Erases the contents of the internal data store
- [public] `compare($string, $length, $caseInsensitive)` -> Binary safe comparison of two string
- [public] `copy()` -> Provides a copy of the StringHelper object
- [public] `data()` -> Provides the internal data store
- [public] `endsWith($string, $caseInsensitive)` -> Determines if the internal data store ends with the given string
- [public] `find($string, $position, $caseInsensitive)` -> Attempts to find a string within the internal data store
- [public] `firstChar()` -> Retrieves the first character in the internal data store
- [public] `isEmptyOrNull()` -> Returns true if the internal data store is empty or null
- [public] `isEmptyOrNullOrWhitespace()` -> Returns true if the internal data store is empty, null, or only contains whitespace characters
- [public] `lastChar()` -> Retrieves the last character in the internal data store
- [public] `length()` -> Returns the length of the internal data store
- [public] `replace($search, $replace, $count)` -> Replaces all occurrences of the search string
- [public] `replaceContained($start, $end, $replace, ` -> Replaces text contained within $start and $end
- [public] `replaceOnce($search, $replace, $position, $caseInsensitive)` -> Tried to find and replace the search string once
- [public] `startsWith($string, $caseInsensitive)` -> Determines if the internal data store starts with the given string
- [public] `subString($start, $length)` -> Returns a part of the internal data store
- [public] `toLower()` -> Converts internal data store to lowercase version of itself
- [public] `toUpper()` -> Converts internal data store to uppercase version of itself
- [public] `__toString()` -> Overrides default __toString() behavior

### StringJoinOptions Helper Class
The `StringJoinOptions` helper class is used by the `StringHelper` class to provide
information used by the `StringHelper::join()` method.

#### Properties
- [public:string] `$glue` -> Glue string to use when joining other strings
- [public:boolean] `$guardGlue` -> Wehther not to guard against glue duplicates while joining strings

#### Methods
- [public] `__construct($glue, $guardGlue)` -> Instantiates a new StringJoinOptions object