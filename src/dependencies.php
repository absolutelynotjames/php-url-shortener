<?php

use Flintstone\Flintstone;

// DIC configuration
$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

//Flinstone database
$container['db'] = function ($c) {
    $settings = $c->get('settings')['db'];
    $urls = new Flintstone('urls', $settings);
    return $urls;
};

//current site url
$container['url'] = function ($c) {
    //protocol
    $protocol = empty($_SERVER['HTTPS']) ? 'http' : 'https';
    // domain name
    $domain = $_SERVER['SERVER_NAME'];
    // server port
    $port = $_SERVER['SERVER_PORT'];
    $disp_port = ($protocol == 'http' && $port == 80 || $protocol == 'https' && $port == 443) ? '' : ":$port";

    // complete url
    $url = "${protocol}://${domain}${disp_port}/";
    return $url;
};

// /new controller
$container['NewController'] = function ($c) {
    $url = $c->get("url"); // retrieve the 'view' from the container
    $db = $c->get("db"); // retrieve the 'view' from the container
    $logger = $c->get("logger"); // retrieve the 'view' from the container

    return new NewController($url, $db, $logger);
};

// Slim Framework CSRF Protection
$container['csrf'] = function ($c) {
    return new \Slim\Csrf\Guard;
};