## FileHelper Class
The `FileHelper` class provides helper methods for common filesystem
operations.

### FileHelperGlobs Enum Constants
- [integer] `GLOB_ALL` -> Returns both files and folders
- [integer] `GLOB_FOLDERS` -> Returns only folders
- [integer] `GLOB_FILES` -> Returns only files

### Properties
- [protected:string] `$relativePath` -> Relative path for this FileHelper instance, replaces '~/' in path strings

### Methods
- [public] `__construct($relativePath, $preIncludes)` -> Instantiates a new FileHelper object, optionally registers already-included files
- [public] `copyFile($source, $destination)` -> Copies a single file between paths
- [public] `copyFolder($source, $destination)` -> Copies an entire folder between paths
- [public] `fileExists($path)` -> Determines if a file exists on the filesystem
- [public] `folderExists($path)` -> Determines if a folder exists on the filesystem
- [public] `getContents($path)` -> Retrieves the contents of the given file
- [public] `getFolderFiles($path)` -> Retrieves all file names in a folder non-recursively
- [public] `getFolderFolders($path)` -> Retrieves all folder names in a folder non-recursively
- [public] `getFolderItems($path, $recursive)` -> Retrieves all items in a folder, optionally recursive
- [public] `getRelativePath()` -> Retrieves the store relative path
- [protected] `globFolder($path, $globType, $recursive)` -> Internal method to traverse a folder's contents and return the requested item types
- [public] `load($path, $allowReload)` -> Attempts to load the file at the given path, optionally allows for a file to be loaded multiple times
- [public] `loadGroup($paths, $allowReload)` -> Attempts to load multiple files, optionally allows for files to be loaded multiple times
- [public] `makeFolder($path, $mode, $recursive)` -> Attempts to create a folder if it doesn't exist
- [public] `pathJoin($start, ...$parts)` -> Joins paths together using the UNIX directory separator
- [protected] `processRoot($path)` -> Internal method to replace the '~/' token in a path
- [public] `putContents($path, $data, $flags, $context)` -> Attempts to write data to a file at the given path
- [protected] `recursiveCopy($source, $dest)` -> Internal method to recursively copy items in a folder
- [public] `removeFile($path)` -> Deletes a file at the path
- [public] `removeFolder($path)` -> Deletes a folder at the path non-recursively
- [public] `touchFile($path, $time, $atime)` -> Sets access and modification time of file at the path