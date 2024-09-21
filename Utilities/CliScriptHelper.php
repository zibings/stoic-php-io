<?php

	namespace Stoic\Utilities;

	/**
	 * Script helper class, to combine some common actions done on all scripts.
	 *
	 * @package Stoic
	 */
	class CliScriptHelper {
		/**
		 * Collection of examples for script.
		 *
		 * @var string[]
		 */
		protected array $examples             = [];
		/**
		 * Collection of options for script.
		 *
		 * @var CliScriptOption[]
		 */
		protected array $options              = [];
		/**
		 * Placeholder for max width of arguments.
		 *
		 * @var int
		 */
		protected int $longOptionWidth  = 1;
		/**
		 * Placeholder for max width of only short arguments.
		 *
		 * @var int
		 */
		protected int $shortOptionWidth = 1;


		/**
		 * Instantiates a new CliScriptHelper object.
		 *
		 * @param string $name Name of the script, displayed at script start.
		 * @param string $description Description of the script, displayed with help.
		 * @param ConsoleHelper $ch ConsoleHelper object for internal use.
		 * @return void
		 */
		public function __construct(
			public string $name,
			public string $description,
			protected ConsoleHelper $ch) {
			return;
		}

		/**
		 * Adds an example to be shown with script help.
		 *
		 * @param string $example Text of example for script.
		 * @return static
		 */
		public function addExample(string $example) : static {
			$this->examples[] = $example;

			return $this;
		}

		/**
		 * Adds an option for use with the script.
		 *
		 * @param string $name Identifier for option.
		 * @param string $shortName Short name/argument for option.
		 * @param string $longName Long name/argument for option.
		 * @param string $shortDescription Short, one-line description for option.
		 * @param string $longDescription Longer description for option.
		 * @param bool $required Whether the option is required by the script.
		 * @param mixed|null $defaultValue Default value for option if not provided at runtime.
		 * @return static
		 */
		public function addOption(string $name, string $shortName, string $longName, string $shortDescription, string $longDescription, bool $required = false, mixed $defaultValue = null) : static {
			if (strtolower($shortName) == 'h' || strtolower($longName) == 'help') {
				throw new \InvalidArgumentException("Cannot use options with either 'h' (short) or 'help' (long) names due to collision with internal 'help' functionality");
			}

			if (empty($shortName) || empty($longName)) {
				throw new \InvalidArgumentException("Must provide both short and long versions of the argument");
			}

			$longLen                = strlen($longName);
			$shortLen               = strlen($shortName);
			$this->longOptionWidth  = ($this->longOptionWidth < $longLen) ? $longLen : $this->longOptionWidth;
			$this->shortOptionWidth = ($this->shortOptionWidth < $shortLen) ? $shortLen : $this->shortOptionWidth;

			$this->options[] = new CliScriptOption(
				$defaultValue,
				$longDescription,
				$longName,
				$name,
				$required,
				$shortDescription,
				$shortName
			);

			return $this;
		}

		/**
		 * Checks a ConsoleHelper object's parameters against the required options for the script.  If the requirements are
		 * not satisfied, this method will print an error and exit the runtime.
		 *
		 * @return void
		 */
		public function checkRequirements() : void {
			if (!$this->satisfiesRequirements()) {
				$requiredLength = 0;
				$required       = [];

				foreach ($this->options as $opt) {
					if ($opt->required) {
						$tmp = "`{$opt->name}`";

						$required[]     = $tmp;
						$requiredLength = (strlen($tmp) > $requiredLength) ? strlen($tmp) : $requiredLength;
					}
				}

				$this->showBasicHelp("Must include " . implode(', ', array_values($required)) . " argument(s)");

				exit;
			}

			return;
		}

		/**
		 * Retrieves all options for the script in an array.
		 *
		 * @return string[]
		 */
		public function getOptions() : array {
			$ret = [];

			foreach ($this->options as $opt) {
				$value = $this->ch->getParameterWithDefault($opt->shortName, $opt->longName, $opt->defaultValue, true);

				$ret[$opt->longName]  = $value;
				$ret[$opt->shortName] = $value;
			}

			return $ret;
		}

		/**
		 * Checks a ConsoleHelper object's parameters against the required options for the script.
		 *
		 * @return bool
		 */
		public function satisfiesRequirements() : bool {
			foreach ($this->options as $opt) {
				if ($opt->required && !$this->ch->hasShortLongArg($opt->shortName, $opt->longName, true)) {
					return false;
				}
			}

			return true;
		}

		/**
		 * Generates a basic instruction on how to get help for the script, optionally displaying the given message before
		 * the instruction.
		 *
		 * @param string|null $message Optional message to send before the generated help instruction.
		 * @return static
		 */
		public function showBasicHelp(string|null $message = null) : static {
			if ($message !== null) {
				$this->ch->putLine($message);
				$this->ch->putLine();
			}

			$this->ch->putLine("Call `php " . $this->ch->getSelf() . " -h` or `php " . $this->ch->getSelf() . " --help` for more information");

			return $this;
		}

		/**
		 * Generates a full set of instructions for using the script, including examples and options.
		 *
		 * @return static
		 */
		public function showOptionHelp() : static {
			if (count($this->options) < 1) {
				$this->showBasicHelp();

				return $this;
			}

			$help = $this->ch->getParameterWithDefault('h', 'help');

			if ($help !== true) {
				$help = strtolower($help);

				foreach ($this->options as $opt) {
					if ($help === strtolower($opt->shortName) || $help === strtolower($opt->longName)) {
						$this->ch->putLine("Basic usage of {$opt->name} option: -{$opt->shortName} | --{$opt->longName}");
						$this->ch->putLine();
						$this->ch->putLine(wordwrap($opt->longDescription));
						$this->ch->putLine();

						break;
					}
				}

				return $this;
			}

			$this->ch->putLine($this->description);
			$this->ch->putLine();

			$this->ch->putLine("Available arguments and examples");
			$this->ch->putLine();

			$required = [];
			$optional = [];
			$optionWidth = strlen("- | --") + $this->longOptionWidth + $this->shortOptionWidth;

			foreach ($this->options as $opt) {
				$shortDesc = wordwrap($opt->shortDescription, 75, str_pad(PHP_EOL, $optionWidth));
				$shortName = str_pad("-{$opt->shortName}", $this->shortOptionWidth + 1, ' ', STR_PAD_LEFT);
				$tmp       = "  " . str_pad("{$shortName} | --{$opt->longName}", $optionWidth) . "  {$shortDesc}";

				if ($opt->required) {
					$required[] = $tmp;
				} else {
					$optional[] = $tmp;
				}
			}

			if (count($required) > 0) {
				$this->ch->putLine("Required Arguments:");
				$this->ch->putLine(implode(PHP_EOL, array_values($required)));
				$this->ch->putLine();
			}

			if (count($optional) > 0) {
				$this->ch->putLine("Optional Arguments:");
				$this->ch->putLine(implode(PHP_EOL, array_values($optional)));
				$this->ch->putLine();
			}

			if (count($this->examples) > 0) {
				$this->ch->putLine("Examples:");
				$this->ch->putLine();
				$this->ch->putLine(implode(PHP_EOL . PHP_EOL, array_values($this->examples)));
				$this->ch->putLine();
			}

			return $this;
		}

		/**
		 * Helper method to begin the execution of a script, displaying the script name in a header, displaying the
		 * script's generated help text if 'h' or 'help' are detected parameters, and finally checking the script's
		 * requirements if toggled (toggled by default). If the requirements are checked and not met, the same message
		 * produced by CliScriptHelper::checkRequirements() is shown and the script will exit.
		 *
		 * @param bool $checkRequirements Optional toggle for checking requirements, defaults to true.
		 * @return static
		 */
		public function startScript(bool $checkRequirements = true) : static {
			$this->ch->putLine($this->name);
			$this->ch->putLine(str_pad('', strlen($this->name), '-'));
			$this->ch->putLine();

			if ($this->ch->hasShortLongArg('h', 'help')) {
				$this->showOptionHelp();

				exit;
			}

			if ($checkRequirements) {
				$this->checkRequirements();
			}

			return $this;
		}
	}
