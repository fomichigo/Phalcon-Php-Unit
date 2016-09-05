<?php
use Phalcon\Di;
use Phalcon\Loader;
use Phalcon\Test\UnitTestCase as PhalconTestCase;

$loader = new Loader();
use Phalcon\Mvc\Model\Manager as ModelsManager;
use Phalcon\Mvc\Model\Metadata\Memory as MetaData;

$loader->registerDirs(
    array(
        __DIR__ . '/../incubator/',
    ), true
)->register();

abstract class CntrlrTestCaseF extends PhalconTestCase
{

    protected $_cache;


    protected $_config;


    private $_loaded = false;

    public function getRouter(){

        $router = new Phalcon\Mvc\Router();
        $router->setDefaultModule("frontend");
        
        $router->add("/iosapi/:params", array(
            'module' => 'frontend',
            'controller' => 'iosapi',
            'action' => 'index',
            'params' => 1
        ));

        $router->add("/iosapi/testform", array(
            'module' => 'frontend',
            'controller' => 'iosapi',
            'action' => 'testform',
            'params' => 1
        ));

        $router->add("/admin", array(
            'module' => 'backend',
            'controller' => 'login',
            'action' => 'index',
        ));
        $router->add("/admin/", array(
            'module' => 'backend',
            'controller' => 'login',
            'action' => 'index',
        ));
        $router->add("/admin/:controller/:action/:params", array(
            'module' => 'backend',
            'controller' => 1,
            'action' => 2,
            'params' => 3
        ));

        $router->add("/", array(
            'controller' => 'login',
            'action' => 'index',
        ));

        $router->notFound( array(
            'module' => 'frontend',
            'controller' => 'errors',
            'action' => 'show404'
        ));
        return $router;
    }


    public function setUp()
    {
        parent::setUp();
        $di = Di::getDefault();
        $di->set('router', function () {return $this->getRouter();});
        $di->setShared('assets', 'Phalcon\Assets\Manager');
        $di->set("request", 'Phalcon\Http\Request');
        $di->set("response", 'Phalcon\Http\Response');
        $di->set('flashSession', 'Phalcon\Flash\Session');
        $this->setDi($di);
        $_SERVER['HTTP_HOST'] = 'site.loc';
        $loader = new \Phalcon\Loader();
        $loader->registerNamespaces(array('Multiple\Frontend' => '../apps/frontend/'))->register();
        $module = new \Multiple\Frontend\Module;
        $module->registerAutoloaders();
        $module->registerServices($this->di);
        $this->dispatcher = $this->di->get('dispatcher');
        $eventManager = new \Phalcon\Events\Manager();
        $this->dispatcher->setEventsManager($eventManager);
        $this->di->set('modelsManager', function() {return new \Phalcon\Mvc\Model\Manager();});
        $this->di->set('modelsMetadata', function() {return new \Phalcon\Mvc\Model\Metadata\Memory();});
        $this->di->set('db', function () {$config = new \Phalcon\Config\Adapter\Ini("../tests/config/config.ini");return new \Phalcon\Db\Adapter\Pdo\Mysql($config->database_test->toArray());});
        $this->di->set('db_remote', function () {$config = new \Phalcon\Config\Adapter\Ini("../tests/config/config.ini");return new \Phalcon\Db\Adapter\Pdo\Mysql($config->database_remote_test->toArray());});
        $this->di->set('config', function () {$config = new \Phalcon\Config\Adapter\Ini("../tests/config/config.ini");return $config->api->toArray();});
        $this->di->set('mail', function () {$config = new \Phalcon\Config\Adapter\Ini("../tests/config/config.ini");return $config->mail->toArray();});
        $this->_loaded = true;
    }


    public function __destruct()
    {
        /*if (!$this->_loaded) {
            throw new \PHPUnit_Framework_IncompleteTestError('Please run parent::setUp().');
        }*/
    }
}