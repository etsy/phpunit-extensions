<?php
/**
 *   Extension of the PHPUnit Framework Test Case with export file functionality.
 *
 */

/** Extension of PHPUnit class that provides export file functionality */
abstract class PHPUnit_FileExport_TestCase extends PHPUnit_Framework_TestCase {
    
    /** Name of the primary directory for class testing exports */
    const sEXPORT_DIR       = "phpunit_exports";

    /** Command-line options for saving exports */
    const sSAVE_ALL_EXPORTS_OPT = "--save-all-exports";
    const sSAVE_EXPORTS_OPT     = "--save-exports";

    /** Max number of differences in strings to be reported */
    const iMAX_STR_DIFFS = 10;
   
    /** Mandatory class method to run the system
     *
     *  @param object  $result  see PHPUnit documentation
     *  @return  unknown see PHPUnit documentation
     */
    public function run (PHPUnit_Framework_TestResult $result = NULL) {
        return parent::run($result);
    }
   
    /** Mandatory class method to get the count
     *
     *  @return  integer count - see PHPUnit documentation
     */
    public function count () {
        return parent::count();
    }

    /** Clean up - remove any backup files if needed
     *  @return void
     */
    public static function tearDownAfterClass () {
        $sBackupDir = self::_backupDirectory();
        $sExportDir = dirname(self::_getExportPath(true));

        if (is_dir($sBackupDir) == false)
            return;
        
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
    }

    /** Compare the variable export of any variable to an existing export file
     *  If the save-export flag has been activated, the results are stored
     *
     *  @param unknown $latest  any structure or value that can be exported
     *  @return void
     */
    public function assertExportCompare ($latest) {
        
        // Get the export path
        $sPath = self::_getExportPath();

        // Get the variable export
        $sExport = var_export($latest, true);

        // Storage flag is set - storing the export
        if (self::_savingExportsFlag() == true) {

            // Back up old files if needed
            $this->_backupExportDir($sPath);
            
            // Add the contents directly
            if (file_put_contents($sPath, $sExport) === false) 
                $this->fail("unable to save export: {$sPath}");
            self::_statusMsg("Compare exported results: {$sPath}");
        }

        // File should exist now
        if (file_exists($sPath) == false)
            $this->fail("export file does not exist: {$sPath}");

        // Compare the results
        $this->assertStrEqualsFile($sPath, $sExport, "Export file: {$sPath}");
    }

    /** Rebuilt version of the method: assertStringEqualsFile
     *  @param $sComparePath1 path of the file
     *  @param $sCompareStr2  string to compare
     *  @param $sMessage
     *  @return void
     */
    public static function assertStrEqualsFile ($sComparePath1, $sCompareStr2, $sMessage) {

        // Get the content of the file and compare against the string
        if (($sCompareStr1 = file_get_contents($sComparePath1)) === false) 
            self::fail("unable to read exported file: {$sComparePath1}");

        // Binary comparison - no difference found
        if (strcmp($sCompareStr1, $sCompareStr2) == 0)
            return;

        $sStatus = $sMessage . PHP_EOL;
        $sStatus .= "Differences found between exported file and variable" . PHP_EOL;
        $sStatus .= "--- Exported file" . PHP_EOL;
        $sStatus .= "+++ Actual variable" . PHP_EOL;

        $sComparePath2 = "/tmp/" . basename($sComparePath1) . ".tmp";
        if (file_put_contents($sComparePath2, $sCompareStr2) == false)
            self::fail("unable to create temporary file: {$sComparePath2}");

        // Get the difference through the utility and throw out valid header lines
        exec("diff -u '{$sComparePath1}' '{$sComparePath2}'", $aOutput);
        if (preg_match('/^---/', $aOutput[0])) {
            array_shift($aOutput);
            array_shift($aOutput);
        }
        $sStatus .= implode(PHP_EOL, $aOutput);
        unlink($sComparePath2);
        self::fail($sStatus);
    }

    /** Backup the export directory if needed
     * 
     *  @param string $sExportPath  export file path
     *  @return  void
     */
    protected function _backupExportDir ($sExportPath) {
        
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
                    if (is_dir($sBackupDir) == false && mkdir($sBackupDir, 0777, true) == false)
                        $this->fail("unable to backup export directory: $sBackupDir");

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
   

    /** Set or get the backup directory
     *
     *  @param string $sPath optional path name
     *  @return  string backup directory if set, otherwise null
     */
    static protected function _backupDirectory ($sPath = null) 
    {
        static $sBackupPath = null;
        if ($sPath != null)
            $sBackupPath = $sPath;
        return $sBackupPath;
    }

    /** Get the export path for the class that is being tested
     *
     *  @param  boolean $bLastPath look for the last path
     *  @return string export path
     */
    protected static function _getExportPath ($bLastPath = false)
    {
        static $aFileIndexes = array();
        static $sLastPath = null;

        if ($bLastPath)
            return $sLastPath;

        // Get the name of the originating function and class
        $aBackTrace = debug_backtrace();
        $sFcnName   = $aBackTrace[2]["function"];
        $sClass     = $aBackTrace[2]["class"];
      
        // Convert any namespaces
        $sClass = strtr($sClass, '\\', '-');
      
        // Export path combines the class and function names with an index
        $sKey = $sClass . "/" . $sFcnName;

        // Set the top export path
        self::_topExportPath(self::sEXPORT_DIR . "/" . $sClass);

        // First time method is called with this key
        if (array_key_exists($sKey, $aFileIndexes) == false)
            $aFileIndexes[$sKey] = 0;

        // Add the main directory, file index and extension to the path
        $sPath = self::sEXPORT_DIR . "/" . $sKey . "." . ++$aFileIndexes[$sKey];

        // Create the directories if needed
        $sDir = dirname($sPath);
        if (file_exists($sDir) == false)
        {
            if (mkdir($sDir, 0777, true) == false)
                $this->fail("unable to create the export directory: $sDir");
        }
      
        // Return the path
        $sLastPath = $sPath;
        return $sPath;
    }

    /** Get or set the top export path (set only once)
     *
     *  @param string $sPath optional path name
     *  @return  string export directory 
     */
    protected static function _topExportPath ($sPath = null) 
    {
        static $sExportPath = null;
        if ($sPath != null && $sExportPath == null)
            $sExportPath = $sPath;
        return $sExportPath;
    }
   
    /** Determine if one of the command-line options have been set for saving exports.  One flag will
     *  save exports for all method.  The other must include the method name as one of the command-line
     *  arguments, e.g.
     *    --save-exports test_get_patient_name
     *    --save-exports get_patient_name
     *  Both these examples do the same thing.  To save all the exports again:
     *    --save-all-exports
     *
     *  @return boolean 
     */
    static protected function _savingExportsFlag () {
        
        global $argv;
        static $aOpts = null;

        // Grab the command-line args once to avoid modifications
        if ($aOpts == null)
            $aOpts = $argv;
      
        // Check for the two flags
        if (in_array(self::sSAVE_ALL_EXPORTS_OPT, $aOpts))
            return true;
        else if (! in_array(self::sSAVE_EXPORTS_OPT, $aOpts))
            return false;

        // Move back the call stack, finding the test function
        for ($n = 2, $aTrace = debug_backtrace(); $n < sizeof($aTrace); $n++) {
            $sTestFcn = $aTrace[$n]["function"];
            if (preg_match("/^test_(.*)/", $sTestFcn, $aArgs)) 
                return (in_array($sTestFcn, $aOpts) || in_array($aArgs[1], $aOpts));
        }
        return false;
    }

    /** Echo a status message
     *  @param string $sMsg
     *  @return void
     */
    protected static function _statusMsg ($sMsg) {
        echo "  {$sMsg}\n";
    }
}


