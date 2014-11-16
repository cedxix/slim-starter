#!/usr/bin/env php
<?php

define("SITEROOT", dirname(__FILE__) . '/');
define("MIGRATIONS", SITEROOT."sql/migrations/");
define("SEEDS", SITEROOT."sql/seeds/");
define("TEMPLATES", SITEROOT."sql/templates/");
define("HISTORY_FILE", SITEROOT."sql/history.json");

define("MIGR_PREFIX", "migr_");
define("SEED_PREFIX", "seed_");
define("MIGR_SUFFIX", "_");
define("SEED_SUFFIX", "_");



date_default_timezone_set('UTC');

require SITEROOT.'vendor/autoload.php';

$app = new stdClass();
require SITEROOT.'app/database.php';


/**
 * Lightweight utility for managing database migrations and seeding.
 *
 * HT: Kyle Ladd's novice class (https://github.com/kladd/slim-eloquent)
 * 
 */
class Lightswitch
{
    /**
     * @var array
     */
    private $args;
    
    /**
     * @var Illuminate\Database\Capsule\Manager
     */
    private $db;

    /**
     * @var array
     */
    private $history;

    /**
     * @var timestamp default value used to init the history
     */
    private $defaultTimestamp = 342144000;

    
    public function __construct(Illuminate\Database\Capsule\Manager $db, $args)
    {
        $this->db = $db;
        $this->args = $args;
        $this->loadHistory();
    }
    
    private function loadHistory()
    {
        //todo: someday let's validate the contents if it does exist
        if (!file_exists(HISTORY_FILE)) {
            $this->history = array(
                'appliedMigrations' => array(), 
                'lastMigration' => $this->defaultTimestamp,
                'seedGroups' => array(
                    'default' => array(
                        'lastSeed' => $this->defaultTimestamp,
                        'appliedSeeds' => array()
                        )
                    ) 
                );
            $this->saveHistory();
        }
       $this->history = json_decode(file_get_contents(HISTORY_FILE));
    }

    private function saveHistory()
    {
        file_put_contents(HISTORY_FILE, json_encode($this->history, JSON_PRETTY_PRINT));
    }


    private function help($oops = false)
    {
        if ($oops) {
            echo "\n";
            echo "Oops! Try something like this...\n";
        }
        $mefile = $this->args[0];
        echo "\n";
        echo "/ - - - - - - - - - - - - - - - - - - - - - - - - -\n";
        echo "| Lightswitch Usage \n";
        echo "| - - - - - - - - - - - - - - - - - - - - - - - - -\n";
        echo "| \n";
        echo "| Run all MIGRATIONS that haven't been run \n";
        echo "|   php $mefile migrate \n";
        echo "| \n";
        echo "| Run all SEEDS that haven't been run \n";
        echo "|   php $mefile seed \n";
        echo "| \n";
        echo "| Display the STATUS of all migrations and seeds \n";
        echo "|   php $mefile status \n";
        echo "| \n";
        echo "| - Design time helpers - - - - -\n";
        echo "| \n";
        echo "| Create a new migration file. \n";
        echo "|   php $mefile new migration [name]\n";
        echo "| \n";
        echo "| Create a new seed file. \n";
        echo "|   php $mefile new seed [name]\n";
        echo "| \n";
        echo "\ - - - - - - - - - - - - - - - - - - - - - - - - -\n";
        echo "\n";
    }


    public function exec()
    {
        // remember, first arg is the name of the currently executing file
        $argCount = count($this->args);

        if ($argCount < 2 || $argCount > 4) {
            $this->help(true);
            return;
        }

        if ($argCount == 2) {
            switch ($this->args[1]) {
                case "migrate":
                    $this->runMigrations();
                    break;
                case "seed":
                    $this->runSeed();
                    break;
                case "status":
                    $this->runStatus();
                    break;
                default:
                    $this->help();
                    break;
            }
            return;
        }

        // handle running of named seed group
        if ($this->args[1] == 'seed') {
            $this->runSeed($this->args[2]);
            return;
        }

        if ($this->args[1] != 'new') {
            $this->help(true);
            return;
        }

        if ($this->args[2] != 'migration' && $this->args[2] != 'seed') {
            $this->help(true);
            return;
        }

        $name = isset($this->args[3])
            ? $this->args[3]
            : '';
            
        switch ($this->args[2]) {
            case "migration":
                $this->newMigration($name);
                break;
            case "seed":
                $this->newSeed($name);
                break;
        }
    }


    private function newMigration($name)
    {
        $cleanName = $this->getCleanName($name);
        $classname = MIGR_PREFIX.time().MIGR_SUFFIX.$cleanName;
        $filename = $classname.'.php';
        $filepath = MIGRATIONS.$filename;

        $template = file_get_contents(TEMPLATES.'migration.php');
        $content = str_replace('{{classname}}', $classname, $template);
        file_put_contents($filepath, $content);
        echo "New migration created at: $filepath \n";
    }


    private function newSeed($name)
    {
        $cleanName = $this->getCleanName($name);
        $classname = SEED_PREFIX.time().SEED_SUFFIX.$cleanName;
        $filename = $classname.'.php';
        $filepath = SEEDS.$filename;

        $template = file_get_contents(TEMPLATES.'seed.php');
        $content = str_replace('{{classname}}', $classname, $template);
        file_put_contents($filepath, $content);
        echo "New seed created at: $filepath \n";
    }


    private function runStatus()
    {
        $dbname = $this->db->getConnection()->getDatabaseName();
        $hasMigratedBefore = $this->history->lastMigration != $this->defaultTimestamp;

        echo "\n";
        echo "/ - - - - - - - - - - - - - - - - - - - - - - - - -\n";
        echo "| Lightswitch Status  \n";
        echo "| Database: $dbname \n";
        echo "| - - - - - - - - - - - - - - - - - - - - - - - - -\n";
        echo "| \n";
        echo "| - Migrations - - - - -\n";
        if ($hasMigratedBefore) {
            foreach ($array as $key => $value) {
                
            }
        } else {
            echo "| No migrations have been run against this database. \n";
        }
        echo "| \n";
        foreach ($this->history->seedGroups as $groupName => $groupHistory) {
            $hasSeededBefore = $groupHistory->lastSeed != $this->defaultTimestamp;
            echo "| - $groupName Seeds - - - - -\n";
            if ($hasSeededBefore) {

            } else {
                echo "| No $groupName seeds have been run against this database. \n";
            }
            echo "| \n";
        }
        echo "\ - - - - - - - - - - - - - - - - - - - - - - - - -\n";
        echo "\n";
        
        //America/Chicago

    }


    private function getCleanName($name)
    {
        if (empty($name)) { return ''; }
        return preg_replace('/[^a-zA-Z0-9]/', '', $name);
    }


    private function runMigrations()
    {
        $files = glob(MIGRATIONS.'*.php');
        foreach ($files as $file) {
            $thisMigrationFailed = true;
            try {
                // todo: add code to skip/continue if this one is older than lastMigration
                require_once($file);
                $className = basename($file, '.php');
                $migrationId = $this->getIdFromClassName($className);
                echo $migrationId . "--\n";

//                $obj = new $className;
//                $obj->run($this->db);
                $thisMigrationFailed = false;
            } catch (\Exception $exc) {
                echo "Migration $className failed. \n";
                echo $exc->getTraceAsString()."\n";
                break;
            } finally {
                if ($thisMigrationFailed) { return; }
                $this->history->lastMigration = $migrationId;
                $this->history->appliedMigrations[] = array(
                    'ranOn' => time(),
                    'class' => $className
                    );
                $this->saveHistory();
            }
        }
    }

    private function getIdFromClassName($className)
    {
        return split('_', $className)[1];
    }

    private function runSeed($group = 'default')
    {
        $files = glob(SEEDS.$group.'/*.php');
        $this->run($files);
    }


    private function run($files)
    {
        foreach ($files as $file) {
            try {


                echo $file."\n";

                require_once($file);
                $class = basename($file, '.php');
                echo $class."\n";

//                $obj = new $class;
//                $obj->run($this->db);

            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
                break;
            }
        }
    }
}

$lightswitch = new Lightswitch($app->db, $argv);
$lightswitch->exec();