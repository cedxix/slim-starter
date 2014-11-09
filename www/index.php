<?php

define("WEBROOT", dirname(__FILE__) . '/');
define("SITEROOT", dirname(dirname(__FILE__)) . '/');

date_default_timezone_set('UTC');

require SITEROOT.'vendor/autoload.php';

// define slim app and basic options
$app = new \SlimController\Slim(array(

    'log.enabled' => true,
    'log.writer' => new \Slim\Logger\DateTimeFileWriter(array(
            'path' => '../logs',
            'name_format' => 'Y-m-d',
            'message_format' => '%label%|%date%|%message%'
        )),

    // - - - - - - - - - -
    // Configure SlimController
    //
    // https://github.com/fortrabbit/slimcontroller
    // - - - - - - - - - -
    'controller.class_prefix'    => '\\App\\Controllers',
    'controller.class_suffix'    => 'Controller',
    'controller.method_suffix'   => 'Action',
    'controller.template_suffix' => 'twig',

    // - - - - - - - - - -
    // Init Twig views
    // - - - - - - - - - -
    'view' => new \Slim\Views\Twig(),
    'templates.path' => __DIR__ . '/../app/Views'

));

// - - - - - - - - - -
// Setup secure session cookies
// - - - - - - - - - -
$app->add(new \Slim\Middleware\SessionCookie(array(
    'expires' => time() + (1 * 24 * 60 * 60), // = 1 day
    'httponly' => true, //means its not available to JavaScript
    'secret' => 'a.super.secret.key.if.ever.there.was.one',
    'cipher' => MCRYPT_RIJNDAEL_256,
    'cipher_mode' => MCRYPT_MODE_CBC
)));


// - - - - - - - - - -
// Setup custom options for Twig
//
// http://twig.sensiolabs.org/doc/api.html#environment-options
// - - - - - - - - - -
$view = $app->view();
$view->parserOptions = array(
    'cache' => __DIR__ . '/../cache'
    , 'strict_variables' => true
    , 'autoescape' => true
    , 'optimizations' => -1
    //, 'debug' => true
);


// - - - - - - - - - -

require SITEROOT.'app/environment.php';
require SITEROOT.'app/hooks.php';
require SITEROOT.'app/routes.php';
require SITEROOT.'app/database.php';

$app->run();

// eof