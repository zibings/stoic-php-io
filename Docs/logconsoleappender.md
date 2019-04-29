## LogConsoleAppender Class
Provides a `Stoic\Log\AppenderBase` appender to output log messages to STDOUT in
the console.

### Properties
- [protected:ConsoleHelper] `$ch` -> Internal ConsoleHelper instance

### Methods
- [public] `__construct($ch)` -> Instantiates a new LogConsoleAppender object
- [public] `process($sender, $dispatch)` -> Processes the `Stoic\Log\MessageDispatch` batch