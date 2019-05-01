## LogFileAppender Class
Provides a `Stoic\Log\AppenderBase` appender to output log messages to a file.

### Properties
- [protected:FileHelper] `$fh` -> Internal `FileHelper` instance
- [protected:string] `$outputFile` -> Path to the output file
- [protected:LogFileOutputTypes] `$outputType` -> Type of output to append to file

### Methods
- [public] `__construct($ch)` -> Instantiates a new LogFileAppender object
- [public] `process($sender, $dispatch)` -> Processes the `Stoic\Log\MessageDispatch` batch