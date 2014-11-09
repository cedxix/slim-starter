<?php

$app->hook('slim.before.dispatch', function () use ($app) 
{

    // - - - - - - - - - - 
    // Allow open routes
    // - - - - - - - - - - 
    if ($app->request()->getPathInfo() === '/') { return; }
    foreach ($app->openRoutePrefixes as $prefix)
    {
        $length = strlen($prefix);
        $prefixMatchesPath = (substr($app->request()->getPathInfo(), 0, $length) === $prefix);
        if ($prefixMatchesPath) { return; }
    }

    
    // - - - - - - - - - - 
    // Redirect if we're not logged in
    // - - - - - - - - - - 
    if (!isset($_SESSION['user.id'])) {
        $_SESSION['urlRedirect'] = $app->request()->getPathInfo();
        $app->flash('error', 'Login required');
        $app->redirect('/login');
    }
    

//    echo '<br><br>Session<br><pre>';
//    echo var_dump($_SESSION);
//    echo '</pre><hr>';

    
});


// eof