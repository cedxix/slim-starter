<?php

//namespace App;

//use Propel\Runtime\Propel;
//use Propel\Runtime\Connection\ConnectionManagerSingle;
//
//
//define('DB_PATH', SITEROOT.'data/database.db');
//define('DB_SETUP_SQL_PATH', SITEROOT.'app/Sql/database.sql');
//
//if (!file_exists(DB_PATH)){
//    $db = new \SQLite3(DB_PATH);
//    $db->exec(file_get_contents(DB_SETUP_SQL_PATH));
//}
//
//
//$serviceContainer = Propel::getServiceContainer();
//$serviceContainer->setAdapterClass('betabuddy_web', 'sqlite');
//$manager = new ConnectionManagerSingle();
//$manager->setConfiguration(array ('dsn' => 'sqlite:'.DB_PATH) );
//$serviceContainer->setConnectionManager('betabuddy_web', $manager);
