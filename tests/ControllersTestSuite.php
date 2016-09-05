<?php
class ControllersTestSuite extends \UnitTestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        \PHPUnit_Framework_TestCase::__construct($name = null, $data = [], $dataName = '');
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite();
        $suite->setRunTestInSeparateProcess(true);
        $suite->addTestFile('controllersTests/RouterTest.php');
        $suite->addTestFile('controllersTests/FrontendTest.php');
        return $suite;
    }
}