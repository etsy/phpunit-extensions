<?php

class PHPUnit_Extensions_PHPUI_ResultPrinter
extends PHPUnit_TextUI_ResultPrinter {
	
	protected function writeProgress($progress) {
        $this->write($progress);
        $this->column++;
        $this->numTestsRun++;

        if ($this->column == $this->maxColumn) {
            $this->write(
              sprintf(
                ' %' . $this->numTestsWidth . 'd',

                $this->numTestsRun
              )
            );

            $this->writeNewLine();
        }
	}
}