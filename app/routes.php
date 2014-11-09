<?php

/**
 * Defines prefixes for routes that will not require authentication
 */
$app->openRoutePrefixes = array(
    '/signup',
    '/login'
);

$app->addRoutes(array(
    // Account
    '/profile' => 'Account:profile',
    '/signup' => 'Account:signup',
    '/login' => 'Account:login',
    '/logout' => 'Account:logout',
    // Home
    '/hello(/:name)' => 'Home:hello',
    '/' => 'Home:index'
));

// eof