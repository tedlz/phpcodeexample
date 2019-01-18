<?php

use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\DI\FactoryDefault;
use Phalcon\Http\Response\Cookies;
use Phalcon\Loader;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\View;
use Phalcon\Session\Adapter\Files as Session;
use Phalcon\Tag;

try {

    // Register an autoloader
    $loader = new Loader();
    $loader->registerDirs(
        array(
            '../app/controllers/',
            '../app/library/',
            '../app/models/',
            '../app/library/Service/',
        )
    );
    $loader->registerNamespaces([
        'App\Controllers' => '../app/controllers/',
        'App\Library' => '../app/library/',
    ]);
    $loader->register();

    require_once __DIR__ . '/../vendor/autoload.php';

    // Create a DI
    $di = new FactoryDefault();

    // Set the database service
    $di['db'] = function () {
        return new DbAdapter(array(
            "host" => "127.0.0.1",
            "username" => "root",
            "password" => "123456",
            "dbname" => "demo",
        ));
    };

    // Setting up the view component
    $di['view'] = function () {
        $view = new View();
        $view->setViewsDir('../app/views/');
        return $view;
    };

    // Setup a base URI so that all generated URIs include the "tutorial" folder
    $di['url'] = function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    };

    // Setup the tag helpers
    $di['tag'] = function () {
        return new Tag();
    };

    $di->set('logger', function ($fileName = '') {
        $fileName = $fileName ?: 'debug.log';
        $fileName = __DIR__ . '/../logs/' . date('Ymd') . "-{$fileName}";
        return new Phalcon\Logger\Adapter\File($fileName);
    });

    $di['router'] = function () {
        $router = new Phalcon\Mvc\Router();
        $router->removeExtraSlashes(true);
        $router->add('/s', 'Index::index');
        $router->setDefaultNamespace('App\Controllers');
        $router->setDefaultAction('index');
        return $router;
    };

    $di['cookies'] = function () {
        $cookies = new Cookies();
        $cookies->useEncryption(false);
        return $cookies;
    };

    $di['session'] = function () {
        $session = new Session();
        $session->start();
        return $session;
    };

    $di['redis'] = function () {
        $redis = new Redis();
        $redis->connect('127.0.0.1', '6379');
        return $redis;
    };

    // Handle the request
    $application = new Application($di);

    echo $application->handle()->getContent();

} catch (Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
