<?php

//use Phalcon\Di;
//use Phalcon\Di\FactoryDefault;

ini_set('display_errors', 1);
error_reporting(E_ALL);
define('APP_PATH', __DIR__.'/../public');
define('ROOT_PATH', __DIR__);
define('PATH_LIBRARY', __DIR__ . '/../app/library/');
define('PATH_SERVICES', __DIR__ . '/../app/services/');
define('PATH_RESOURCES', __DIR__ . '/../app/resources/');

set_include_path(
    ROOT_PATH . PATH_SEPARATOR . get_include_path()
);
// требуется для phalcon/incubator
include __DIR__ . "/../vendor/autoload.php";

// Используем автозагрузчик приложений для автозагрузки классов.
// Автозагрузка зависимостей, найденных в composer.
$loader = new \Phalcon\Loader();

$loader->registerDirs(
    array(
        ROOT_PATH,
        '../apps/library/',
        '../apps/library/xpress',
        '../apps/library/PHPMailer',
    )
)->registerNamespaces(array(
    'Multiple\Library'             => '../apps/library/',
));

$loader->register();

if (!defined('PHPUNIT_COMPOSER_INSTALL')) {
    define('PHPUNIT_COMPOSER_INSTALL', __DIR__ . '/../vendor/autoload.php');
}