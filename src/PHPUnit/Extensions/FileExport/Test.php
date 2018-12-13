<?php

namespace PHPUnit\Extensions\FileExport;

use PHPUnit\Framework;

/**
 *   Extension of the PHPUnit Framework Test Case with export file functionality.
 *
 */

/** Extension of PHPUnit class that provides export file functionality */
abstract class TestCase extends Framework\TestCase {
    
    /** * @const name of the primary directory for class testing exports */
    const sEXPORT_DIR = "phpunit_exports";

    /** @const command-line options for saving ALL exports */
    const sSAVE_ALL_EXPORTS_OPT = "save-all-exports";

    /** @const command-line option for saving exports for a filtered test */
    const sSAVE_EXPORTS_OPT = "save-exports";

    /** @const max number of differences in strings to be reported */
    const iMAX_STR_DIFFS = 10;

    /** @var string */
    protected static $_sBackupPath = self::sEXPORT_DIR;


    /**
     * Clean up any unneeded backup directories
     * @return void
     */
    protected function tearDown () {
        $sBackupDir = self::_backupDirectory();
        $sExportDir = dirname(self::_getExportPath(true));

        if (! is_dir($sBackupDir)) {
            return;
        }
        
        if ($sBackupDir && ($dh = opendir($sBackupDir)) !== false) {
            
            while (($sFile = readdir($dh)) !== false) {
                if (preg_match("/\w/", $sFile) && file_exists("{$sExportDir}/{$sFile}") &&
                    md5_file("{$sBackupDir}/{$sFile}") == md5_file("{$sExportDir}/{$sFile}")) {
                    self::_statusMsg("Exact match with previous export: {$sFile}");
                    unlink("{$sBackupDir}/{$sFile}");
                }
            }
            closedir($dh);
        }

        // Backup directory is empty - remove
        if (count(scandir($sBackupDir)) == 2) {
            rmdir($sBackupDir);
            self::_statusMsg("Removing unneeded backup directory");
        }
        
        parent::tearDown();
    }

    /**
     *  Compare the variable export of any variable to an existing export file
     *  If the save-export flag has been activated, the results are stored
     *
     *  @param mixed $latest  any structure or value that can be exported
     *  @return void
     */
    public static function assertExportCompare ($latest) {
        
        // Get the export path
        $sPath = self::_getExportPath();

        // Get the variable export
        $sExport = var_export($latest, true);

        // Storage flag is set - storing the export
        if (self::_isSavingExport()) {

            self::_statusMsg("");

            // Back up old files if needed
            self::_backupExportDir($sPath);
            
            // Add the contents directly
            if (file_put_contents($sPath, $sExport) === false) {
                self::assertFileIsWritable($sPath, "unable to save export: {$sPath}");
            }
            self::_statusMsg("Compare exported results: {$sPath}");
        }

        // File should exist now
        self::assertFileExists($sPath, "export file does not exist: {$sPath}");

        // Compare the results
        self::assertStrEqualsFile($sPath, $sExport, "Export file: {$sPath}");
    }

    /** Rebuilt version of the method: assertStringEqualsFile
     *  @param string $sComparePath1 path of the file
     *  @param string $sCompareStr2  string to compare
     *  @param string $sMessage
     *  @return void
     */
    public static function assertStrEqualsFile (string $sComparePath1, string $sCompareStr2, string $sMessage) {

        // Get the content of the file and compare against the string
        self::assertFileIsReadable($sComparePath1, "unable to read exported file: {$sComparePath1}");
        $sCompareStr1 = file_get_contents($sComparePath1);

        // Binary comparison - no difference found
        if (strcmp($sCompareStr1, $sCompareStr2) == 0) {
            return;
        }

        $sStatus = $sMessage . PHP_EOL;
        $sStatus .= "Differences found between exported file and variable" . PHP_EOL;
        $sStatus .= "--- Exported file" . PHP_EOL;
        $sStatus .= "+++ Actual variable" . PHP_EOL;

        // Create the temporary file with the results
        $sComparePath2 = sprintf("/tmp/%s.tmp", basename($sComparePath1));
        if (file_put_contents($sComparePath2, $sCompareStr2) === false) {
            self::assertFileIsWritable($sComparePath2, "unable to create temporary file: {$sComparePath2}");
        }

        // Get the difference through the utility and throw out valid header lines
        exec("diff -u '{$sComparePath1}' '{$sComparePath2}'", $aOutput);
        if (preg_match('/^---/', $aOutput[0])) {
            array_shift($aOutput);
            array_shift($aOutput);
        }
        $sStatus .= implode(PHP_EOL, $aOutput);
        unlink($sComparePath2);
        self::_failMsg($sStatus);
    }

    /**
     * Backup the export directory if needed
     * @param string $sExportPath  export file path
     * @return void
     */
    protected static function _backupExportDir (string $sExportPath) {
        
        global $argv;
        static $bDirCreated = false;

        $sExportDir  = dirname($sExportPath);
        $sExportFile = basename($sExportPath);

        $bGlobalBackup = in_array(self::sSAVE_ALL_EXPORTS_OPT, $argv);

        // Backup directory has not completed
        if ($bDirCreated == false) {
            $bDirCreated = true;

            // Search for creation time of a valid export file
            if ($dh = opendir($sExportDir)) {
                
                // Find the latest timestamp
                $iTime = 0;
                while (($sFile = readdir($dh)) !== false) {
                    if (preg_match("/\.[0-9]+$/", $sFile)) {
                        $iNewTime = filectime($sExportDir . "/" . $sFile);
                        if ($iNewTime > $iTime)
                            $iTime = $iNewTime;
                    }
                }

                // Time is valid - create a backup directory
                if ($iTime > 0) {
                    
                    // Directory name contains the date and time
                    $sBackupDir = $sExportDir . "/backup_" . date("Y_m_d_H-i-s", $iTime);
                    self::_backupDirectory($sBackupDir);

                    // Attempt to create the directory
                    if (is_dir($sBackupDir) == false && mkdir($sBackupDir, 0777, true) == false) {
                        self::assertDirectoryExists($sBackupDir, "unable to backup export directory: {$sBackupDir}");
                    }

                    // Global backup - insert all export files into the backup directory
                    if ($bGlobalBackup) {
                        rewinddir($dh);
                        while (($sFile = readdir($dh)) !== false)  {
                            if (preg_match("/\.[0-9]+$/", $sFile)) {
                                self::_statusMsg("Export file backed up: {$sBackupDir}/{$sFile}");
                                rename($sExportDir . "/" . $sFile, $sBackupDir . "/" . $sFile);
                            }
                        }
                    }
                }
                closedir($dh);
            }
        }

        // Not a global backup and running the first time - attempt to rename the specific methods
        if (! $bGlobalBackup && substr($sExportFile, -2) == '.1') {

            if ($dh = opendir($sExportDir)) {

                // Remove .1 from ending of filename
                $sMatch = substr($sExportFile, 0, -2);
                
                // Rename all matches
                while (($sFile = readdir($dh)) !== false) {
                    if (preg_match("/{$sMatch}\.[0-9]+$/", $sFile)) {
                        self::_statusMsg("Export file backed up: {$sBackupDir}/{$sFile}");
                        rename($sExportDir . "/" . $sFile, $sBackupDir . "/" . $sFile);
                    }
                }
                closedir($dh);
            }
        }
    }
   

    /**
     * Set or get the backup directory
     * @param string $sPath optional path name
     * @return string
     */
    static protected function _backupDirectory (string $sPath = null) {
        if ($sPath) {
            self::$_sBackupPath = $sPath;
        }
        return self::$_sBackupPath;
    }

    /**
     * Get the export path for the class that is being tested
     *  @param  bool $bLastPath look for the last path
     *  @return string
     */
    protected static function _getExportPath (bool $bLastPath = false) {

        static $aFileIndexes = [];
        static $sLastPath    = null;

        if ($bLastPath) {
            return $sLastPath;
        }

        // Get the name of the originating function and class
        $aBackTrace = debug_backtrace();
        $sFcnName   = $aBackTrace[2]["function"];
        $sClass     = $aBackTrace[2]["class"];
      
        // Convert any namespaces
        $sClass = strtr($sClass, '\\', '-');
      
        // Export path combines the class and function names with an index
        $sKey = $sClass . "/" . $sFcnName;

        // First time method is called with this key
        if (array_key_exists($sKey, $aFileIndexes) == false)
            $aFileIndexes[$sKey] = 0;

        // Add the main directory, file index and extension to the path
        $sPath = self::sEXPORT_DIR . "/" . $sKey . "." . ++$aFileIndexes[$sKey];

        // Create the directories if needed
        $sDir = dirname($sPath);
        if (file_exists($sDir) == false) {
            if (mkdir($sDir, 0777, true) == false) {
                self::assertDirectoryExists($sDir, "unable to create the export directory: {$sDir}");
            }
        }
      
        // Return the path
        $sLastPath = $sPath;
        return $sPath;
    }

    /**
     * Determine if one of the command-line options have been set for saving exports
     * @return bool
     */
    static protected function _isSavingExport () {
        
        global $argv;

        // Saving everything, so always true
        if (in_array(self::sSAVE_ALL_EXPORTS_OPT, $argv)) {
            return true;
        }

        // No individual flag, so always false
        if (! in_array(self::sSAVE_EXPORTS_OPT, $argv)) {
            return false;
        }

        // Move back the call stack, finding the test function
        for ($n = 2, $aTrace = debug_backtrace(); $n < sizeof($aTrace); $n++) {
            $sTestFcn = $aTrace[$n]["function"];
            if (preg_match("/^test_?(.*)/", $sTestFcn, $aArgs)) {
                return (in_array($sTestFcn, $argv) || in_array($aArgs[1], $argv));
            }
        }

        return false;
    }

    /**
     * Echo a status message
     * @param string $sMsg
     * @return void
     */
    protected static function _statusMsg (string $sMsg) {
        echo "  {$sMsg}\n";
    }

    /**
     * @param string $sMessage
     * @return void
     */
    protected static function _failMsg (string $sMessage) {
        $oConstraint = new Framework\Constraint\IsFalse;
        self::assertThat(true, $oConstraint, $sMessage);
    }

}
