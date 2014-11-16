<?php

use Illuminate\Database\Capsule\Manager as Capsule;

define('DB_PATH', SITEROOT.'data/app.db');
//define('DB_SETUP_SQL_PATH', SITEROOT.'app/Sql/database.sql');

if (!file_exists(DB_PATH)){
    $dbHandle = new \SQLite3(DB_PATH);
    // consider adding a migrations table
    // $dbHandle->exec(file_get_contents(DB_SETUP_SQL_PATH));
}

$app->db = new Capsule();

/* Sqlite example */
$app->db->addConnection(array(
    'driver'    => 'sqlite',
    'database'  => DB_PATH,
    'prefix'    => '',
));



/* MySql example
$app->db->addConnection(array(
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'database',
    'username'  => 'root',
    'password'  => 'password',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
));
*/



// Set the event dispatcher used by Eloquent models... (optional)
//use Illuminate\Events\Dispatcher;
//use Illuminate\Container\Container;
//$app->db->setEventDispatcher(new Dispatcher(new Container));

// Set the cache manager instance used by connections... (optional)
//$app->db->setCacheManager(...);

// Make this Capsule instance available globally via static methods... (optional)
$app->db->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$app->db->bootEloquent();


$schema = $app->db->schema();
$schema->dropIfExists('blog');

$schema->create('blog', function($table) {
    $table->increments('id');
    $table->string('slug', 200)->unique();
    $table->string('title', 200)->unique();
    $table->string('foo', 200)->unique();
    $table->integer('category_id');
    $table->text('intro');
    $table->text('body');

    $table->index('slug');
    $table->index('category_id');
});

