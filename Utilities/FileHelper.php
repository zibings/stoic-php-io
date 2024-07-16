<?php

	namespace Stoic\Utilities;

	/**
	 * Enumerated types of glob requests used internally with FileHelper::globFolder().
	 *
	 * @package Stoic\IO
	 * @version 1.0.1
	 */
	class FileHelperGlobs extends EnumBase {
		const GLOB_ALL = 0;
		const GLOB_FOLDERS = 1;
		const GLOB_FILES = 2;
	}

	/**
	 * Class for common filesystem operations.
	 *
	 * @package Stoic\IO
	 * @version 1.1.0
	 */
	class FileHelper {
		/**
		 * Array to store cache of included files.
		 *
		 * @var string[]
		 */
		private static array $included = [];
		/**
		 * String that will replace '~' in paths.
		 *
		 * @var string
		 */
		protected string $relativePath;


		/**
		 * Instantiates new FileHelper class.
		 *
		 * @param string $relativePath String value of relative path, replaces '~' in paths.
		 * @param string[] $preIncludes Array of files that have already been included in runtime.
		 * @throws \InvalidArgumentException Thrown if core path provided is invalid/non-existent.
		 */
		public function __construct(string $relativePath, array $preIncludes = null) {
			if (!is_dir($relativePath)) {
				throw new \InvalidArgumentException("Invalid core path provided for FileHelper instance.");
			}

			$this->relativePath = $relativePath;

			if ($preIncludes !== null) {
				foreach ($preIncludes as $inc) {
					FileHelper::$included[$inc] = true;
				}
			}

			return;
		}

		/**
		 * Copies a single file between paths if file exists at source and does not already exist at destination.
		 *
		 * @param string $source String value of file source path, must exist and be non-null.
		 * @param string $destination String value of file destination path, must not exist and be non-null.
		 * @throws \InvalidArgumentException|\RuntimeException
		 * @return void
		 */
		public function copyFile(string $source, string $destination) : void {
			if (empty($source) || empty($destination)) {
				throw new \InvalidArgumentException("Invalid source or destination path provided to FileHelper::copyFile() -> " . $source . ", " . $destination);
			}

			if (str_ends_with($source, '/') || str_ends_with($destination, '/')) {
				throw new \InvalidArgumentException("Neither source nor destination to FileHelper::copyFile() can be directories -> " . $source . ", " . $destination);
			}

			if (!$this->fileExists($source) || $this->fileExists($destination)) {
				throw new \InvalidArgumentException("Source file didn't exist or destination already exists in FileHelper::copyFile() -> " . $source . ", " . $destination);
			}

			if (!copy($this->processRoot($source), $this->processRoot($destination))) {
				// @codeCoverageIgnoreStart
				throw new \RuntimeException("Failed to copy source file, check PHP logs for more information -> " . $source);
				// @codeCoverageIgnoreEnd
			}

			return;
		}

		/**
		 * Copies an entire folder between paths if folder exists at source and does not exist at destination.
		 *
		 * @param string $source String value of folder source path, must exist and be non-null.
		 * @param string $destination String value of folder destination path, must not exist and be non-null.
		 * @throws \InvalidArgumentException
		 * @return void
		 */
		public function copyFolder(string $source, string $destination) : void {
			if (empty($source) || empty($destination)) {
				throw new \InvalidArgumentException("Invalid source or destination path provided to FileHelper::copyFolder() -> " . $source . ", " . $destination);
			}

			if (!$this->folderExists($source) || $this->folderExists($destination)) {
				throw new \InvalidArgumentException("Source directory didn't exist or destination already exists in FileHelper::copyFolder() -> " . $source . ", " . $destination);
			}

			$this->recursiveCopy($source, $destination);

			return;
		}

		/**
		 * Determines if a file exists at the given path.
		 *
		 * @param string $path String value of potential file path.
		 * @return bool
		 */
		public function fileExists(string $path) : bool {
			if (!empty($path) && is_file($this->processRoot($path))) {
				return true;
			}

			return false;
		}

		/**
		 * Determine if a folder exists at the given path.
		 *
		 * @param string $path String value of potential folder path.
		 * @return bool
		 */
		public function folderExists(string $path) : bool {
			if (!empty($path) && is_dir($this->processRoot($path))) {
				return true;
			}

			return false;
		}

		/**
		 * Retrieves the contents of the file.
		 *
		 * @param string $path String value of file path.
		 * @throws \InvalidArgumentException
		 * @return string
		 */
		public function getContents(string $path) : string {
			if (!$this->fileExists($path)) {
				throw new \InvalidArgumentException("Non-existent file provided to FileHelper::getContents() -> " . $path);
			}

			return file_get_contents($this->processRoot($path));
		}

		/**
		 * Retrieves all file names in a folder non-recursively.
		 *
		 * @param string $path String value of folder path.
		 * @return null|array
		 */
		public function getFolderFiles(string $path) : ?array {
			return $this->globFolder($path, FileHelperGlobs::GLOB_FILES);
		}

		/**
		 * Retrieves all folder names in a folder non-recursively.
		 *
		 * @param string $path String value of folder path.
		 * @return null|array
		 */
		public function getFolderFolders(string $path) : ?array {
			return $this->globFolder($path, FileHelperGlobs::GLOB_FOLDERS);
		}

		/**
		 * Retrieves all item names in a folder with option to do so recursively.
		 *
		 * @param string $path String value of folder path.
		 * @param bool $recursive Boolean value to toggle recursive traversal, default is false.
		 * @return null|array
		 */
		public function getFolderItems(string $path, bool $recursive = false) : ?array {
			return $this->globFolder($path, FileHelperGlobs::GLOB_ALL, $recursive);
		}

		/**
		 * Retrieves the stored relative path value for this instance.
		 *
		 * @return string
		 */
		public function getRelativePath() : string {
			return $this->relativePath;
		}

		/**
		 * Internal method to traverse a folder's contents with option to do so recursively.  Must specify return type via
		 * $globType parameter.
		 *
		 * @param string $path String value of folder path.
		 * @param int $globType Integer value of return type, can be 0 (all), 1 (folders only), and 2 (files only).
		 * @param bool $recursive Boolean value to toggle recursive traversal, default is false.
		 * @return null|array
		 */
		protected function globFolder(string $path, int $globType, bool $recursive = false) : ?array {
			if (empty($path)) {
				return null;
			}

			$ret  = [];
			$path = $this->processRoot($path);

			if (!is_dir($path)) {
				return null;
			}

			if (!str_ends_with($path, '/')) {
				$path .= '/';
			}

			if ($dh = @opendir($path)) {
				while (($item = @readdir($dh)) !== false) {
					if ($item == '.' || $item == '..') {
						continue;
					}

					if (is_dir($path . $item)) {
						if ($globType < FileHelperGlobs::GLOB_FILES) {
							if ($recursive) {
								// @codeCoverageIgnoreStart
								$tmp = $this->globFolder($path . $item, $globType, $recursive);

								if (count($tmp) > 0) {
									foreach ($tmp as $titem) {
										$ret[] = $titem;
									}
								}
								// @codeCoverageIgnoreEnd
							}

							$ret[] = $path . $item . '/';
						}
					} else if ($globType != FileHelperGlobs::GLOB_FOLDERS) {
						$ret[] = $path . $item;
					}
				}
			}

			@closedir($dh);

			sort($ret);

			return $ret;
		}

		/**
		 * Attempts to load the given file as a PHP file. Caches all successful loads and by default will disallow reload.
		 *
		 * @param string $path String value of file to attempt loading.
		 * @param bool $allowReload Boolean value to allow reload if file has already been loaded, default is false.
		 * @throws \InvalidArgumentException|\RuntimeException
		 * @return string
		 */
		public function load(string $path, bool $allowReload = false) : string {
			if (empty($path)) {
				throw new \InvalidArgumentException("Invalid file path provided for FileHelper::load()");
			}

			if (array_key_exists($path, FileHelper::$included) && !$allowReload) {
				return $path;
			}

			if (!$this->fileExists($path)) {
				throw new \InvalidArgumentException("Invalid file provided for FileHelper::load() -> " . $path);
			}

			FileHelper::$included[$path] = true;
			require($this->processRoot($path));

			return $path;
		}

		/**
		 * Attempts to load the given files as PHP files. Caches all successful loads and by default will disallow reload.
		 *
		 * @param string[] $paths Array of string values for files to attempt loading.
		 * @param bool $allowReload Boolean value to allow reload if files have already been loaded, default is false.
		 * @throws \InvalidArgumentException|\RuntimeException
		 * @return string[]
		 */
		public function loadGroup(array $paths, bool $allowReload = false) : array {
			if (count($paths) < 1) {
				return [];
			}

			$ret = [];

			foreach ($paths as $path) {
				$ret[] = $this->load($path, $allowReload);
			}

			return $ret;
		}

		/**
		 * Attempts to create a folder if it doesn't exist.
		 *
		 * @param string $path String value of path for folder to create.
		 * @param int $mode Permission mode to attempt applying to created path (ignored on Windows), defaults to 0777.
		 * @param bool $recursive Whether to create the path recursively, defaults to false.
		 * @return bool
		 */
		public function makeFolder(string $path, int $mode = 0777, bool $recursive = false) : bool {
			if (empty($path) || $this->folderExists($path)) {
				return false;
			}

			return mkdir($this->processRoot($path), $mode, $recursive);
		}

		/**
		 * Joins paths parts together using the UNIX style directory separator.
		 *
		 * @param string $start Initial path part, only trailing slashes are managed.
		 * @param string[] $parts Additional path parts, final path part only manages leading slashes.
		 * @return string
		 */
		public function pathJoin(string $start, string ...$parts) : string {
			$path = array();
			$start = str_replace("\\", "/", $start);
			$partsCount = count($parts) - 1;

			if ($partsCount < 0) {
				return $this->processRoot($start);
			}

			if (strlen($start) > 1 && str_ends_with($start, '/')) {
				$start = substr($start, 0, strlen($start) - 1);
			}

			$path[] = $start;

			for ($i = 0; $i < $partsCount; $i++) {
				$part = str_replace("\\", "/", $parts[$i]);

				if ($part[0] == '/') {
					$part = substr($part, 1);
				}

				if (str_ends_with($part, '/')) {
					$part = substr($part, 0, strlen($part) - 1);
				}

				$path[] = $part;
			}

			$end = str_replace("\\", "/", $parts[$partsCount]);

			if ($end[0] == '/') {
				$end = substr($end, 1);
			}

			$path[] = $end;

			return $this->processRoot(implode('/', array_values($path)));
		}

		/**
		 * Internal method to change '~' prefix into core path.
		 *
		 * @param string $path String value of path to process.
		 * @return string
		 */
		protected function processRoot(string $path) : string {
			if ($path[0] == '~') {
				$path = $this->relativePath . substr($path, ($path[1] == '/' && $this->relativePath[strlen($this->relativePath) - 1] == '/') ? 2 : 1);
			}

			return $path;
		}

		/**
		 * Attempts to write data to file at path.
		 *
		 * @param string $path String value of file path.
		 * @param mixed $data Data to write to file, see http://php.net/file_put_contents for full details.
		 * @param int $flags Optional flags to use for writing, see http://php.net/file_put_contents for full details.
		 * @param resource $context Optional stream context to use for writing, see http://php.net/file_put_contents for full details.
		 * @throws \InvalidArgumentException
		 * @return mixed
		 */
		public function putContents(string $path, mixed $data, int $flags = 0, $context = null) : mixed {
			if (empty($path)) {
				throw new \InvalidArgumentException("Invalid file provided to FileHelper::putContents() -> " . $path);
			}

			if (empty($data)) {
				throw new \InvalidArgumentException("No data provided to FileHelper::putContents(), should call FileHelper::touchFile() -> " . $path);
			}

			$args = [$this->processRoot($path), $data];

			// @codeCoverageIgnoreStart
			if ($flags > 0) {
				$args[] = $flags;
			}

			if ($context !== null) {
				$args[] = $context;
			}
			// @codeCoverageIgnoreEnd

			return call_user_func_array('file_put_contents', $args);
		}

		/**
		 * Internal method to traverse a folder's items recursively and copy them to a new destination.
		 *
		 * @param string $source String value of source folder, must exist and be non-null.
		 * @param string $dest String value of destination folder, must not exist and be non-null.
		 * @throws \InvalidArgumentException|\RuntimeException
		 * @return void
		 */
		protected function recursiveCopy(string $source, string $dest) : void {
			if (!str_ends_with($source, '/')) {
				$source .= '/';
			}

			if (!str_ends_with($dest, '/')) {
				$dest .= '/';
			}

			if (!$this->folderExists($source) || $this->folderExists($dest)) {
				// @codeCoverageIgnoreStart
				throw new \InvalidArgumentException("Source directory didn't exist or destination directory does exist in FileHelper::recursiveCopy() -> " . $source . ", " . $dest);
				// @codeCoverageIgnoreEnd
			}

			$source = $this->processRoot($source);
			$dest = $this->processRoot($dest);

			$dh = @opendir($source);
			@mkdir($dest);

			while (($item = @readdir($dh)) !== false) {
				if ($item == '.' || $item == '..') {
					continue;
				}

				if (is_dir($source . $item)) {
					$this->recursiveCopy($source . $item, $dest . $item);
				} else {
					if (!copy($source . $item, $dest . $item)) {
						// @codeCoverageIgnoreStart
						@closedir($dh);

						throw new \RuntimeException("Failed to copy item in FileHelper::recursiveCopy() -> " . $source . ", " . $dest);
						// @codeCoverageIgnoreEnd
					}
				}
			}

			@closedir($dh);

			return;
		}

		/**
		 * Deletes a file.
		 *
		 * @param string $path Path to the file.
		 * @return bool
		 */
		public function removeFile(string $path) : bool {
			if (empty($path)) {
				return false;
			}

			return unlink($this->processRoot($path));
		}

		/**
		 * Deletes a directory.
		 *
		 * @param string $path Path to the directory.
		 * @return bool
		 */
		public function removeFolder(string $path) : bool {
			if (empty($path)) {
				return false;
			}

			return rmdir($this->processRoot($path));
		}

		/**
		 * Sets access and modification time of file.
		 *
		 * @param string $path String value of file path.
		 * @param null|int $time The touch time.  If $time is not supplied, the current system time is used.
		 * @param null|int $atime If present, the access time of the given filename is set to the value of atime. Otherwise, it is set to the value passed to the time parameter. If neither are present, the current system time is used.
		 * @return bool
		 */
		public function touchFile(string $path, ?int $time = null, ?int $atime = null) : bool {
			if (empty($path)) {
				return false;
			}

			$path = $this->processRoot($path);
			$time = ($time === null) ? time() : $time;
			$args = array($path, $time);

			if ($atime !== null) {
				$args[] = $atime;
			}

			return call_user_func_array('touch', $args);
		}
	}
