<?php
namespace TodoApp;

use Puppy\Application;
use Puppy\DI\Container;
use Puppy\DI\IContainer;
use Puppy\Http\Request;
use Puppy\ModelFactory;
use Puppy\Storing\Config;
use Puppy\ViewFactory;
use TodoApp\Controllers\Main;

DEFINE('PUBLIC_DIR', dirname(__FILE__));
DEFINE('ROOT_DIR', dirname(PUBLIC_DIR));

require_once('../../vendor/autoload.php');

$container = new Container();

$container->Set('config', function($container){
    return Config::FromPhp(ROOT_DIR . '/todoapp.cfg.php');
});

$container->Set('viewFactory', function(IContainer $container){
    /** @var Config $config */
    $config = $container->Get('config');

    return new ViewFactory($config->Get('views.path', ROOT_DIR . '/views'));
});

$container->Set('pdo', function(IContainer $container){
    /** @var Config $config */
    $config = $container->Get('config');
    $dsn = 'mysql:host=%s;db=%s';

    return new \PDO(
        sprintf($dsn, $config->Get('db.host'), $config->Get('db.name')),
        $config->Get('db.user'),
        $config->Get('db.pass')
    );
});

$container->Set('modelFactory', function(IContainer $container){
    /** @var \PDO $pdo */
    $pdo = $container->Get('db');

    return new ModelFactory($pdo);
});

$todoapp = new Application($container);

// --- Объявление маршрутов
$todoapp->Route('frontend', '/^\/(?<action>[A-z0-9_\-]+)$/', Main::class, '%action%');

$response = $todoapp->Handle(Request::FromGlobal());
$response->Send();