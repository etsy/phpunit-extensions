<?php

require_once 'PHPUnit/Autoload.php';

class PHPUnit_Experimental_PHPUI_Command {

    private $filename;

    private $arguments;
    private $result;
    private $suites;

    public function __construct(
    	$filename,
    	PHPUnit_Framework_TestResult $result = null,
    	array $suites = array(),
    	array $arguments = array(
    		'verbose' => false,
    		'debug' => false,
    		'colors' => false,
    		'filter' => false,
    		'processIsolation' => false,
            'groups' => array(),
            'excludeGroups' => array(),
    	)
    ) {
    	$this->filename = $filename;
    	$this->result = ($result === null) ? new PHPUnit_Framework_TestResult() : $result;
    	$this->suites = $suites;
    	$this->arguments = $arguments;
    }
    
    public function log($format, $file_name, $log_incomplete_skipped = true) {
    	switch (strtolower($format)) {
    		case 'junit':
    		    $this->result->addListener(
    		    	new PHPUnit_Util_Log_JSON($file_name)
    		    );
    		    break;
    		case 'tap':
    		    $this->result->addListener(
    		    	new PHPUnit_Util_Log_TAP($file_name)
    		    );
    		    break;
    		case 'json':
    		    $this->result->addListener(
    		    	new PHPUnit_Util_Log_JUnit($file_name, $log_incomplete_skipped)
    		    );
    		    break;
    		case 'plain':
    		    $this->result->addListener(
    		    	new PHPUnit_TextUI_ResultPrinter(
    		    		$file_name,
    		    		true
    		    	)
    		    );
    		    break;
    		default:
    		    throw new InvalidArgumentException(
    		    	sprintf(
    		    		'Invalid format: %s.  Must be "junit", "tap", "json", or "plain".',
    		    		$format
    		    	)
    		    );
    	}
    	return $this;
    }

    public function coverage(PHP_CodeCoverage $coverage, $format, $file_or_directory_name) {
    	$this->result->setCodeCoverage($coverage);

    	switch (strtolower($format)) {
    		case 'clover':
    		    $this->arguments['coverageClover'] = $file_or_directory_name;
    		    break;
    		case 'html':
    		    $this->arguments['reportDirectory'] = $file_or_directory_name;
    		    break;
    		case 'php':
    		    $this->arguments['coveragePHP'] = $file_or_directory_name;
    		    break;
    		case 'text':
    		    $this->arguments['coverageText'] = $file_or_directory_name;
    		    break;
    		default:
    		    throw new InvalidArgumentException(
    		    	sprintf(
    		    		'Invalid format: %s.  Must be "clover", "html", "php", or "text".',
    		    		$format
    		        )
    		    );
    	}
         return $this;
    }

    public function testdox($format, $file_name) {
    	switch (strtolower($format)) {
            case 'html':
                $this->result->addListener(
                	new PHPUnit_Util_TestDox_ResultPrinter_HTML($file_name)
                );
                break;
            case 'text':
                $this->result->addListener(
                	new PHPUnit_Util_TestDox_ResultPrinter_Text($file_name)
                );
                break;
            default:
                throw new InvalidArgumentException(
                	sprintf(
                		'Invalid format: %s.  Must be "html" or "text".',
                		$format
                	)
                );
    	}
        return $this;
    }

    public function filter($pattern) {
        $this->arguments['filter'] = $pattern;
        return $this;
    }

    public function includeGroup($group) {
        if (!isset($this->arguments['groups'])) {
            $this->arguments['groups'] = array();
        }
        $this->arguments['groups'][] = $group;
        return $this;
    }

    public function excludeGroup($group) {
        if (!isset($this->arguments['excludeGroups'])) {
        	$this->arguments['excludeGroups'] = array();
        }
        $this->arguments['excludeGroups'][] = $group;
        return $this;
    }

    public function repeat($times) {
        $this->arguments['repeat'] = $times;
        return $this;
    }

    public function colors($enable = true) {
        $this->arguments['colors'] = $enable;
        return $this;
    }

    public function convertToException($error_level, $enable = true) {
    	switch (strtolower($error_level)) {
    		case 'notices':
    		    PHPUnit_Framework_Error_Notice::$enabled = $enable;
    		    break;
    		case 'warnings':
    		    PHPUnit_Framework_Error_Warning::$enabled = $enable;
    		    break;
    		case 'errors':
    		    $this->result->convertErrorsToExceptions($enable);
    		    break;
    		default:
    		    throw new InvalidArgumentException(
    		    	sprintf(
    		    		'Invalid error level: %s.  Must be "notices", "warnings", or "errors".',
    	                $error_level
    	            )
    		    );
    	}
    	return $this;
    }

    public function stopOn($test_status, $enable = true) {
        switch (strtolower($test_status)) {
            case 'failure':
                $this->result->stopOnFailure($enable);
                break;
            case 'error':
                $this->result->stopOnError($enable);
                break;
            case 'incomplete':
                $this->result->stopOnIncomplete($enable);
                break;
            case 'skipped':
                $this->result->stopOnSkipped($enable);
                break;
            default:
                throw new InvalidArgumentException(
                	sprintf(
                		'Invalid test status: %s.  Must be "failure", "error", "incomplete", or "skipped".',
                		$test_status
                    )
                );
        }
        return $this;
    }

    public function timeoutForSize($test_size, $timeout) {
        switch (strtolower($test_size)) {
        	case 'small':
        	    $this->result->setTimeoutForSmallTests($timeout);
        	    break;
        	case 'medium':
        	    $this->result->setTimeoutForMediumTests($timeout);
        	    break;
        	case 'large':
        	    $this->result->setTimeoutForLargeTests($timeout);
        	    break;
        	default:
        	    throw new InvalidArgumentException(
        	    	sprintf(
        	    		'Invalid test size: %s.  Must be "small", "medium", or "large".',
        	    		$test_size
        	        )
        	    );
        }
        return $this;
    }

    public function strict($enable = true) {
        $this->result->strictMode($enable);
        return $this;
    }

    public function verbose($enable = true) {
        $this->arguments['verbose'] = $enable;
        return $this;
    }

    public function debug($enable = true) {
        $this->arguments['debug'] = $enable;
        return $this;
    }

    public function processIsolation($enable = true) {
        $this->arguments['processIsolation'] = $enable;
        return $this;
    }

    public function backupGlobals($enable = true) {
        $this->arguments['backupGlobals'] = $enable;
        return $this;
    }

    public function backupStaticAttributes($enable = true) {
        $this->arguments['backupStaticAttributes'] = $enable;
        return $this;
    }

    public function addTestSuite(PHPUnit_Framework_TestSuite $test_suite) {
        if (!isset($this->suites)) {
            $this->suites = array();
        }
        $this->suites[] = $test_suite;
        return $this;
    }

    public function addTestListener(PHPUnit_Framework_TestListener $listener) {
        $this->result->addListener($listener);
        return $this;
    }

    public function run($exit = true) {
    	if (!isset($this->coverage_filter)) {
    		$this->coverage_filter = new PHP_CodeCoverage_Filter();
    	}

        $printer = new PHPUnit_Experimental_PHPUI_ResultPrinter(
    	    NULL,
    		$this->arguments['verbose'],
    		$this->arguments['colors'],
    		$this->arguments['debug']
    	);
    	$this->result->addListener($printer);
    	$this->result->addListener(new PHPUnit_Util_DeprecatedFeature_Logger);

        try {
            foreach ($this->suites as $suite) {
    		    $this->runSuite($suite);
    		    unset($suite);
            }
    	    $this->result->flushListeners();
    	    $printer->printResult($this->result);
        } catch (PHPUnit_Framework_Exception $e) {
        	print $e->getMessage() . "\n";
        }

        // TODO Add coverage reporting

        $ret = PHPUnit_TextUI_TestRunner::FAILURE_EXIT;

        if (isset($this->result) && $this->result->wasSuccessful()) {
        	$ret = PHPUnit_TextUI_TestRunner::SUCCESS_EXIT;
        } else  if (!isset($this->result) || $this->result->errorCount() > 0) {
        	$ret = PHPUnit_TextUI_TestRunner::EXCEPTION_EXIT;
        }

        if ($exit) {
        	exit($ret);
        } else {
        	return $ret;
        }
	}

	private function runSuite(PHPUnit_Framework_TestSuite $suite) {
    	if ($this->arguments['backupGlobals'] === false) {
    		$suite->setBackupGlobals(false);
    	}
    	if ($this->arguments['backupStaticAttributes'] === true) {
    		$suite->setBackupStaticAttributes(true);
    	}
    	if (is_integer($this->arguments['repeat'])) {
    	    $suite = new PHPUnit_Extensions_RepeatedTest(
    	        $suite,
    	        $this->arguments['repeat'],
    	        $this->arguments['filter'],
    	        $this->arguments['groups'],
    	        $this->arguments['excludeGroups'],
    	       	$this->arguments['processIsolation']
    	       );
    	}
    	$suite->run(
    		$this->result,
    		$this->arguments['filter'],
    		$this->arguments['groups'],
    		$this->arguments['excludeGroups'],
    		$this->arguments['processIsolation']
    	);
	}
}