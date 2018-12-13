<?php

use PHPUnit\Extensions\FileExport;

class ExportTest extends FileExport\TestCase {

    public function testExportObject () {
        $aSampleObject1 = new SampleClass('Joan of Arc', 'Domrémy, France', 'joan@foo.com');
        $this->assertExportCompare($aSampleObject1);

        $aSampleObject2 = new SampleClass('Hank Hill', '84 Rainey Street, Arlen, TX', 'hank@foo.com');
        $this->assertExportCompare($aSampleObject2);
    }

    public function testExportArray () {
        $aSampleArray1 = [ 'this', 'is an array', 'of different types', [ 'many dimensions', 1, 2, 3 ] ];
        $this->assertExportCompare($aSampleArray1);

        $aSampleArray2 = [ [ 'one', 'two' ], 'and three' ];
        $this->assertExportCompare($aSampleArray2);
    }
}

class SampleClass {

    public function __construct (string $sName, string $sAddress, string $sEmail) {
        $this->sName    = $sName;
        $this->sAddress = $sAddress;
        $this->sEmail   = $sEmail;
    }

    public $sName;

    public $sAddress;

    public $sEmail;
}