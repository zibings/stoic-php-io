<?php

	namespace Stoic\IO;

	/**
	 * Class FileHelper
	 *
	 * @package Stoic\IO
	 * @version 1.0.0
	 */
	class FileHelper {

		const FOLDERS_AND_FILES = 0;
		const FOLDERS = 1;
		const FILES = 2;

		/**
		 * Cache collection of files that have
		 * been included.
		 *
		 * @var array
		 */
		private static $_includes = array();

		/**
		 * The relative directory path.
		 *
		 * @var string
		 */
		private $_relDir;

		/**
		 * Creates a new FileHelper instance.
		 *
		 * @param string $relDir  Optional relative directory, './' used otherwise.
		 * @param array $preLoads Optional array of files that have already been loaded.
		 */
		public function __construct($relDir = null, array $preLoads = null) {
			if ($relDir === null) {
				$this->_relDir = './';
			}

			if ($preLoads !== null) {
				foreach ($preLoads as $load) {
					FileHelper::$_includes[$load] = true;
				}
			}
		}

		/**
		 * Copies a file to $Dest on the filesystem.
		 *
		 * @param mixed $source      String value of source file path.
		 * @param mixed $destination String value of desitation file path.
		 *
		 * @return ReturnHelper A ReturnHelper instance with extra state information.
		 */
		public function copyFile($source, $destination) {
			$ret = new ReturnHelper();

			if (empty($source) || empty($destination)) {
				$ret->makeBad()
					->addMessage("Invalid source or destination path provided.");

			} else if (substr($source, -1) == "/" || substr($destination, -1) == "/") {
				$ret->makeBad()
					->addMessage("Neither source or destination can be directories.");

			} else if (!$this->fileExists($source) || $this->fileExists($destination)) {
				$ret->makeBad()
					->addMessage("Source file didn't exist or destination file already exists.");

			} else {
				if (!copy($this->processRoot($source), $this->processRoot($destination))) {
					$ret->makeBad()
						->addMessage("Failed to copy file, check PHP logs for error.");

				} else {
					$ret->addResult($this->processRoot($destination));
				}
			}

			return $ret;
		}

		/**
		 * Recursively copies a directory's contents
		 * to $Dest on the filesystem.
		 *
		 * @param mixed $source      String value of source directory path.
		 * @param mixed $destination String value of destination directory path.
		 *
		 * @return ReturnHelper A ReturnHelper instance with extra state information.
		 */
		public function copyFolder($source, $destination) {
			$ret = new ReturnHelper();

			if (empty($source) || empty($destination)) {
				$ret->addMessage("Invalid source or destination path provided.");

			} else if (!$this->folderExists($source) || $this->folderExists($destination)) {
				$ret->addMessage("Source directory didn't exist or destination folder already exists.");
			} else {
				$ret = $this->recursiveCopy($source, $destination);
			}

			return $ret;
		}

		/**
		 * Returns whether or not a file exists.
		 *
		 * @param string $path String value of file path.
		 *
		 * @return bool True if file exists, false otherwise.
		 */
		public function fileExists($path) {
			if (!empty($path) && file_exists($this->processRoot($path))) {
				return true;
			}

			return false;
		}

		/**
		 * Returns whether or not a folder exists.
		 *
		 * @param string $path String value of folder path.
		 *
		 * @return bool True if folder exists, false otherwise.
		 */
		public function folderExists($path) {
			if (!empty($path) && is_dir($this->processRoot($path))) {
				return true;
			}

			return false;
		}

		/**
		 * Loads a file from the filesystem.
		 *
		 * @param string $path      String value of path to include.
		 * @param bool $allowReload Boolean value to toggle allowing files to be re-loaded.
		 *
		 * @return ReturnHelper A ReturnHelper instance with extra state information.
		 */
		public function load($path, $allowReload = false) {
			$ret = new ReturnHelper();

			if (empty($path)) {
				$ret->addMessage("Invalid path provided.");

			} else if (isset(FileHelper::$_includes[$path]) && !$allowReload) {
				$ret->addMessage("File has already been included.");

			} else {
				FileHelper::$_includes[$path] = true;
				$path = $this->processRoot($path);

				if ($this->fileExists($path)) {
					require($path);

					$ret->makeGood()->addResult($path);

				} else {
					$ret->addMessage("File did not exist.");
				}
			}

			return $ret;
		}

		/**
		 * Return the contents of the specified file.
		 *
		 * @param string $path String value of file path.
		 *
		 * @return string|null String contents of file, null if not found.
		 */
		public function getContents($path) {
			if (empty($path)) {
				return null;
			}

			$path = $this->processRoot($path);
			$ret  = '';

			if ($this->fileExists($path)) {
				$ret = file_get_contents($path);
			}

			return $ret;
		}

		/**
		 * Retrieve file list from specified folder.
		 *
		 * @param string $path String value of the folder path.
		 *
		 * @return array|null List of files, null if not found or path invalid.
		 */
		public function getFolderFiles($path) {
			return $this->globFolder($path, self::FILES);
		}

		/**
		 * Retrieve folder list from specified folder.
		 *
		 * @param string $path    String value of the folder path.
		 * @param bool $recursive Toggles recursive traversal of the folder.
		 *
		 * @return array|null List of folders, null if not found or path invalid.
		 */
		public function getFolderFolders($path, $recursive = false) {
			return $this->globFolder($path, self::FOLDERS, $recursive);
		}

		/**
		 * Retrieves a list of folder items from the specified folder.
		 *
		 * @param string $path    String value of the folder path.
		 * @param bool $recursive Toggles recursive traversal of the folder.
		 *
		 * @return array|null List of folder items, null if not found or path invalid.
		 */
		public function getFolderItems($path, $recursive = false) {
			return $this->globFolder($path, self::FOLDERS_AND_FILES, $recursive);
		}

		/**
		 * Returns the relative directory path.
		 *
		 * @return string Relative directory path for instance.
		 */
		public function getRelativeDir() {
			return $this->_relDir;
		}

		/**
		 * Retrieves the results of a folder traversal.
		 *
		 * @param string $path    String value of the folder path.
		 * @param int $globType   Option for returned entries.
		 * @param bool $recursive Toggles recursive traversal of the folder.
		 *
		 * @return array|null List of folder items, null if not found or path invalid.
		 */
		protected function globFolder($path, $globType, $recursive = false) {
			if (empty($path)) {
				return null;
			}

			$ret = array();
			$path = $this->processRoot($path);

			if (!is_dir($path)) {
				return null;
			}

			if (substr($path, -1) != '/') {
				$path .= '/';
			}

			if ($dir = @opendir($path)) {
				while (($item = @readdir($dir)) !== false) {
					if ($item == '.' || $item == '..') {
						continue;
					}

					if (is_dir($path . $item) && ($globType == self::FOLDERS_AND_FILES || $globType == self::FOLDERS)) {
						$ret[] = $path . $item . '/';

						if ($recursive) {
							$tmp = $this->globFolder($path . $item, $globType, $recursive);

							if (count($tmp) > 0) {
								foreach (array_values($tmp) as $tmpItem) {
									$ret[] = $tmpItem;
								}
							}
						}
					} else if ($globType == self::FOLDERS_AND_FILES || $globType == self::FILES) {
						$ret[] = $path . $item;
					}
				}
			}

			return $ret;
		}

		/**
		 * Creates a folder on the filesystem.
		 *
		 * @param string $path String value of the folder path.
		 *
		 * @return bool True if the folder is created, false otherwise.
		 */
		public function makeFolder($path) {
			if (empty($path) || $this->folderExists($path)) {
				return false;
			}

			return mkdir($this->processRoot($path));
		}

		/**
		 * Processes a path string to replace the ~
		 * with the relative directory path.
		 *
		 * @param string $path String value of the path.
		 *
		 * @return string String value of processed path.
		 */
		protected function processRoot($path) {
			if ($path[0] == '~') {
				$path = $this->_relDir . substr($path, ($path[1] == '/' && $this->_relDir[strlen($this->_relDir) - 1] == '/') ? 2 : 1);
			}

			return $path;
		}

		/**
		 * Inserts the contents into the given file.
		 *
		 * @param string $path      String value of file path.
		 * @param mixed $data       Data to insert into file.
		 * @param int $flags        Optional flags sent to file_put_contents.
		 * @param resource $context Optional context resource from stream_context_create().
		 *
		 * @return ReturnHelper A ReturnHelper instance with extra state information.
		 */
		public function putContents($path, $data, $flags = 0, $context = null) {
			$ret = new ReturnHelper();

			if (empty($path)) {
				$ret->addMessage("Invalid path provided.");

			} else if ($data === null || empty($data)) {
				$ret->addMessage("Invalid data provided.");

			} else {
				if (($bytesWritten = @file_put_contents($this->processRoot($path), $data, $flags, $context)) !== false) {
					$ret->makeGood()->addResult($bytesWritten);

				} else {
					$ret->addMessage("Failed to write to file.");
				}
			}

			return $ret;
		}

		/**
		 * Recursively copies directory contents.
		 *
		 * @param mixed $source String value of source directory path.
		 * @param mixed $destination   String value of destination directory path.
		 *
		 * @return ReturnHelper A ReturnHelper instance with extra state information.
		 */
		protected function recursiveCopy($source, $destination) {
			$ret = new ReturnHelper();
			$ret->makeGood();

			if (substr($source, -1) != '/') {
				$source .= '/';
			}

			if (substr($destination, -1) != '/') {
				$destination .= '/';
			}

			$source = $this->processRoot($source);
			$destination = $this->processRoot($destination);

			$dir = opendir($source);
			@mkdir($destination);

			$ret->addResult($destination);

			while (($file = readdir($dir)) !== false) {
				if ($file == '.' || $file == '..') {
					continue;
				}

				if (is_dir($source . $file)) {
					$recur = $this->recursiveCopy($source . $file, $destination . $file);

					if ($recur->isBad()) {
						$ret->makeBad();
						$ret->addMessages($recur->getMessages());
					} else {
						$ret->addResults($recur->getResults());
					}
				} else {
					if (!copy($source . $file, $destination . $file)) {
						$ret->makeBad();
						$ret->addMessage("Failed to copy '" . $source . $file . "' to '" . $destination . $file . "'");
					} else {
						$ret->addResult($destination . $file);
					}
				}
			}

			closedir($dir);

			return $ret;
		}
	}
