<?php


// -- DEVELOPMENT (default) ------------------
// SetEnv SLIM_MODE development
// fastcgi_param SLIM_MODE "development";
$app->configureMode('development', function () use ($app) {
    $app->config(array(
        'debug' => true
        , 'log.level' => \Slim\Log::DEBUG
    ));

    $app->view()->parserExtensions = array(
        new Twig_Extension_Debug(),
    );
    $app->view()->parserOptions['debug'] = true;
    
});



// -- TESTING --------------------------------
// SetEnv SLIM_MODE testing
// fastcgi_param SLIM_MODE "testing";
$app->configureMode('testing', function () use ($app) {
    $app->config(array(
        'debug' => false
        , 'log.level' => \Slim\Log::NOTICE
    ));
});



// -- PRODUCTION ------------------------------
// SetEnv SLIM_MODE production
// fastcgi_param SLIM_MODE "production";
$app->configureMode('production', function () use ($app) {
    $app->config(array(
        'debug' => false
        , 'log.level' => \Slim\Log::NOTICE
        
    ));
});


// eof