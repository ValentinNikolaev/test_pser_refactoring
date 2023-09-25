<?php

declare(strict_types=1);

namespace unit\Cli;

use UnitTester;

class AppCliTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    public function testMissingArgument(): void
    {
        try {
            $this->tester->runShellCommand("php app.php");
        } catch (\Exception $e) {
        }
        $this->tester->seeResultCodeIs(1);
        $this->tester->seeInShellOutput("EXCEPTION: Missing first argument with filename/file dir");
    }

    // tests

    public function testNonExistsFile(): void
    {
        $filename = "somasdsad";
        try {
            $this->tester->runShellCommand("php app.php $filename");
        } catch (\Exception $e) {
        }
        $this->tester->seeResultCodeIs(1);
        $this->tester->seeInShellOutput("EXCEPTION: File '$filename' doesnt exists");
    }

    protected function _before()
    {
    }
    /** here also cn be tests for non readable files e.t.c */
}