<?php
// DIC configuration
$container = $app->getContainer();

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------

// Twig
$container['view'] = function ($c) {
    $settings = $c->get('settings');
    $view = new Slim\Views\Twig($settings['view']['template_path'], $settings['view']['twig']);

    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
    $view->addExtension(new Twig_Extension_Debug());

    return $view;
};

// Flash messages
$container['flash'] = function ($c) {
    return new Slim\Flash\Messages;
};

// -----------------------------------------------------------------------------
// Service factories
// -----------------------------------------------------------------------------

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings');
    $logger = new Monolog\Logger($settings['logger']['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['logger']['path'], Monolog\Logger::DEBUG));
    return $logger;
};

//database PDO
$container['db'] = function ($c){
    $settings = $c->get('settings')['db'];
    $server = $settings['driver'].":host=".$settings['host'].";dbname=".$settings['dbname'];
    $conn = new PDO($server, $settings["user"], $settings["pass"]);  
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $conn;
};

//database eloquent
$container['dbe'] = function ($container) {
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($container['settings']['dbe']);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};

// -----------------------------------------------------------------------------
// Controller factories
// -----------------------------------------------------------------------------

$container[App\Action\HomeAction::class] = function ($c) {
    return new App\Action\HomeAction($c->get('view'), $c->get('logger'));
};

$container[App\Action\UsersController::class] = function ($c) {
    return new App\Action\UsersController($c);
};

$container[App\Action\CobaController::class] = function ($c) {
    $view = $c->get('view');
    $logger = $c->get('logger');
    $table = $c->get('dbe')->table('users');
    return new \App\Action\CobaController($view, $logger, $table);
};

$container[App\Action\ProductsController::class] = function ($c) {
    $view = $c->get('view');
    $logger = $c->get('logger');
    $table = $c->get('dbe')->table('products');
    return new \App\Action\ProductsController($view, $logger, $table);
};

$container[App\Action\CategoryController::class] = function ($c) {
    $view = $c->get('view');
    $logger = $c->get('logger');
    $table = $c->get('dbe')->table('category');
    return new \App\Action\CategoryController($view, $logger, $table);
};

$container[App\Action\CartController::class] = function ($c) {
    $view = $c->get('view');
    $logger = $c->get('logger');
    $table = $c->get('dbe')->table('cart');
    return new \App\Action\CartController($view, $logger, $table);
};

$container[App\Action\TransactionController::class] = function ($c) {
    $view = $c->get('view');
    $logger = $c->get('logger');
    $table = $c->get('dbe')->table('transactions');
    return new \App\Action\TransactionController($view, $logger, $table);
};

$container[App\Action\DetailTransController::class] = function ($c) {
    $view = $c->get('view');
    $logger = $c->get('logger');
    $table = $c->get('dbe')->table('detail_transactions');
    return new \App\Action\DetailTransController($view, $logger, $table);
};