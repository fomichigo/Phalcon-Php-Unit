<?php

namespace Test;
use Phalcon\Http\Client\Exception;

/**
 * Class UnitTest
 */
class FrontendTest extends \CntrlrTestCaseF
{

    public function setUp()
    {
        sleep(rand(1, 3));
        parent::setUp();
    }

    /**
     * @dataProvider RouterDataProvider
     * @runInSeparateProcess1
     * @medium
     */

    public function testRouter($c, $a, $p = [], $post = [], $get = [], $session = [], $regext = ['(.*)'], $notRegext = [])
    {
        foreach($post as $key=>$item) $_POST[$key] = $item;
        foreach($get as $key=>$item) $_GET[$key] = $item;
        foreach($session as $key=>$item) $_SESSION[$key] = $item;
        $this->dispatcher->setModuleName('frontend');
        $this->dispatcher->setControllerName($c);
        $this->dispatcher->setActionName($a);
        $this->dispatcher->setParams($p);
        $controller = $this->dispatcher->dispatch();
        $controller->view->start()->render($this->dispatcher->getControllerName(), $this->dispatcher->getActionName(), $controller->view->getParamsToView())->finish();
        $string = $controller->view->getContent();
        foreach($regext as $regext_i)
            $this->assertRegExp($regext_i, $string, 'NOT WORK WITH => '.
                json_encode(
                    ['c'=> $c,
                    'a'=> $a,
                    'p'=> $p,
                    'post'=> $post,
                    'get' => $get,
                    'session' => $session,
                    'regext'=> $regext]));
        foreach($notRegext as $regext_i)
            $this->assertRegExp($regext_i, $string, 'NOT WORK WITH(NEGATIVE) => '.
                json_encode(
                        ['c'=> $c,
                        'a'=> $a,
                        'p'=> $p,
                        'post'=> $post,
                        'get' => $get,
                        'session' => $session,
                        'regext'=> $regext,
                        'notRegext'=>$notRegext]));
        $_POST =[];
        $_SESSION = [];
        $_GET = [];
    }


    public static function RouterDataProvider(){
        return [
            //LOGIN
            ['c'=>'login', 'a'=>'index'],
            ['c'=>'login', 'a'=>'logout'],
            ['c'=>'login', 'a'=>'start'],
            ['c'=>'login', 'a'=>'landing'],
            ['c'=>'login', 'a'=>'restorepassword'],
            ['c'=>'login', 'a'=>'restorepassword', 'p'=>[], 'post'=>['email'=>'test@mail.ru']],
            ['c'=>'login', 'a'=>'start', 'p'=>[], 'post'=>['email'=>'vendor@mail.ru', 'password'=>'test']],
            ['c'=>'dashboard', 'a'=>'index', 'p'=>[], 'post'=>[], 'get' => [], 'session' => ['auth'=>['id'=>5]]],
            ['c'=>'errors', 'a'=>'show404', 'p'=>[], 'post'=>[], 'get' => [], 'session' => [], 'regext'=>['/(?=.*Found)(?=.*Page)(?=.*Not)/']],
            ['c'=>'profile', 'a'=>'index', 'p'=>[], 'post'=>[], 'get' => [], 'session' => ['auth'=>['id'=>2]], 'regext'=>['/(?=.*prof-firstName)/']],
        ];
    }

    public function tearDown()
    {
        parent::tearDown();
    }

}
