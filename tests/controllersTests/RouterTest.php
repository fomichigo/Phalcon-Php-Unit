<?php

class RouterTest extends \CntrlrTestCaseF
{

    public function getTestArray(){
        return array(
            array('uri' => '/','controller' => 'login','action' => 'index','params' => array()),
            array('uri' => '','controller' => 'login','action' => 'index','params' => array()),
            array('uri' => '1','controller' => 'errors','action' => 'show404','params' => array()),
            array('uri' => '/login/logout','controller' => 'login','action' => 'logout','params' => array()),
            /*admin part*/
        );
    }
    private function _runTest($router, $test)
    {
        $router->handle($test['uri']);

        $this->assertEquals($router->getControllerName(), $test['controller'], 'Controller Route Filure');
        $this->assertEquals($router->getActionName(), $test['action'], 'Action Route Filure');
        $this->assertEquals($router->getParams(), $test['params'], 'Params Route Filure');
    }


    public function testRouter()
    {

        $tests = $this->getTestArray();
        $router = $this->getRouter();
        foreach ($tests as $n => $test) {
            $this->_runTest($router, $test);
        }

    }
}