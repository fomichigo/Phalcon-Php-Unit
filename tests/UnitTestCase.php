<?php
if(!defined('APP_PATH'))
    define('APP_PATH', __DIR__.'/../public');
require_once  __DIR__ . "/TestHelper.php";
use Phalcon\Di;
use Phalcon\Loader;
use Phalcon\Test\UnitTestCase as PhalconTestCase;
$loader = new Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    array(
        APP_PATH . '/../vendor/',
    ), true
)->register();

abstract class UnitTestCase extends PhalconTestCase
{
    /**
     * @var \Voice\Cache
     */
    protected $_cache;

    public static $_hash = false;

    /**
     * @var \Phalcon\Config
     */
    protected $_config;

    /**
     * @var bool
     */
    private $_loaded = false;

    public function setUp()
    {
        $_SERVER['SERVER_NAME'] = 'site.loc';
        $_SERVER['HTTP_HOST'] = 'site.loc';
        parent::setUp();

        // Загрузка дополнительных сервисов, которые могут потребоваться во время тестирования
        $di = Di::getDefault();

        $di->set('config',function(){
            $config = new \Phalcon\Config\Adapter\Ini("../tests/config/config.ini");
            return $config->api->toArray();
        });
        $di->set('modelsManager', function () {
            return new \Phalcon\Mvc\Model\Manager();
        });
        $di->set('modelsMetadata', function () {
            return new \Phalcon\Mvc\Model\Metadata\Memory();
        });
        $di->set('db', function () {
            $config = new \Phalcon\Config\Adapter\Ini("../tests/config/config.ini");
            return new \Phalcon\Db\Adapter\Pdo\Mysql($config->database_test->toArray());
        });
        $di->set('db_remote', function () {
            $config = new \Phalcon\Config\Adapter\Ini("../tests/config/config.ini");
            return new \Phalcon\Db\Adapter\Pdo\Mysql($config->database_remote_test->toArray());
        });

        $this->setDi($di);

        $this->_loaded = true;
    }

    /**
     * Проверка на то, что тест правильно настроен
     *
     * @throws \PHPUnit_Framework_IncompleteTestError;
     */
    public function __destruct()
    {
        /*if (!$this->_loaded) {
            throw new \PHPUnit_Framework_IncompleteTestError('Please run parent::setUp().');
        }*/
    }
}