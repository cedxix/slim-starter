<?php

namespace App\Controllers;

use App\Forms\Login;

class BaseController extends \SlimController\SlimController
{
    
    protected $viewbag;
    public $layout = "Default.twig";
    
    // Sample form action...
    /*
    public function sampleFormAction($urlParamHereIfWeHaveOne)
    {
        if ($this->app->request()->isPost()) {
            try {
                $model = FormName::getFromPostData($this->app->request->params('FormName'));
                if ($model->isValid) {
        
                    // do stuff

                    // set a flash success message
                    $this->app->flash('success', 'Congratulations! Action successful.');

                    // redirect to next step
                    $this->redirect('/next/step/url');
                }

                // Oops! we must have an error..
                $this->app->flash('error', 'Oops! Something appears to be wrong with your information.');
                $forms['FormName'] = $model;
                $this->render("Current/View", array('forms' => $forms));

            } catch (\Exception $e) {
                //todo: figure out how we want to handle this stuff
                $this->app->log->error($this->PackE($e));
                //var_dump($e);
            }            
        } else {
            $forms['FormName'] = new FormName();
            $this->render("Current/View", array('forms' => $forms));
        }
    }    
    */

    public function __construct(\Slim\Slim &$app) 
    {
        parent::__construct($app);
        
        $this->viewbag = new \stdClass();
        $this->SetDefaults();
    }
    
    public function SetDefaults() {}
    
    public function PackViewbag($args = array())
    {
        $this->viewbag->layout = $this->layout;
        $this->viewbag->isDebug = $this->app->config('debug');
        $this->viewbag->flash = $_SESSION['slim.flash'];

        $this->viewbag->user = array(
            'id' => isset($_SESSION['user.id']) ? $_SESSION['user.id'] : null,
            'name' => isset($_SESSION['user.name']) ? $_SESSION['user.name'] : null,
            'email' => isset($_SESSION['user.email']) ? $_SESSION['user.email'] : null
        );

        if (!isset($_SESSION['user.id'])) {
            $this->viewbag->forms['Login'] = new Login();
        }

        if ($args) {
            $this->viewbag = array_replace_recursive((array)$this->viewbag, $args);
        }
        
        return array('viewbag' => $this->viewbag);
    }
    
    public function render($template, $args = array()) 
    {
        parent::render($template, $this->PackViewbag($args));
    }
    
    public function PackE(\Exception $exception) {
        return 'code:' . $exception->getCode()
                . '|msg:' . $exception->getMessage()
                . '|line:' . $exception->getLine()
                . '|file:' . $exception->getFile()
        ;
    }



    protected function StartUserSession()//User $user)
    {
        $_SESSION['user.id'] = "1234";//$user->getId();
        $_SESSION['user.name'] = "Joe Schmoe";//$user->getFriendlyName();
        $_SESSION['user.email'] = "joe@schmoe.com";//$user->getEmail();
    }

    protected function KillUserSession()
    {
        unset($_SESSION['user.id']);
        unset($_SESSION['user.name']);
        unset($_SESSION['user.email']);
    }


}