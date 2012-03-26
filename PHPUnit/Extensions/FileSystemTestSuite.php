<?php

class PHPUnit_Extensions_FileSystemTestSuite
extends PHPUnit_Framework_TestSuite {
	
	private $root_directory;

	private $directories;
	private $files;
	private $exclude_directories;

	private $prefix;
	private $suffix;

	private $_count;

	public function __construct(
		$root_directory,
		array $directories = array(),
		array $files = array(),
		array $exclude_directories = array(),
	    $prefix = '',
	    $suffix = 'Test.php',
		$theClass = '',
		$name = ''
	) {
		parent::__construct($theClass, $name);
		$this->root_directory = $root_directory;
		$this->directories = $directories;
		$this->files = $files;
		$this->exclude_directories = $exclude_directories;
		$this->prefix = $prefix;
		$this->suffix = $suffix;
	}

    /**
     * {@inheritDoc}
     */
	public function count() {
		if (!isset($this->_count)) {
			$this->_count = parent::count();
		}
		return $this->_count;
	}

    /**
     * {@inheritDoc}
     */
	public function run(
		PHPUnit_Framework_TestResult $result = null,
		$filter = false,
		array $groups = array(),
		array $excludeGroups = array(),
		$processIsolation = false
	) {
    	parent::run($result, $filter, $groups, $excludeGroups, $processIsolation);
        $args = func_get_args();
            $factory = new File_Iterator_Factory();
            $iterator = $factory->getFileIterator(
                $this->directories,
                $this->suffix,
                $this->prefix,
                $this->exclude_directories
            );
            foreach ($iterator as $item) {
                    $this->createAndPerform(
                        $item->getRealPath(),
                        'run',
                        $args
                    );  
            }
            foreach ($this->files as $file) {
                    $this->createAndPerform(
                        $this->root_directory . '/' . $file,
                        'run',
                        $args
                    );
            }
	}

    private function createAndPerform($filename, $test_suite_function, array $args = array()) {
        $suite = new PHPUnit_Framework_TestSuite();
        $suite->addTestFile($filename);
        $suite->setBackupGlobals($this->backupGlobals);
        $suite->setBackupStaticAttributes($this->backupStaticAttributes);
        $result = call_user_func_array(array($suite, $test_suite_function), $args);
        unset($suite);
        return $result;
    }
}