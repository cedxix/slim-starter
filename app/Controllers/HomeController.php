<?php

namespace App\Controllers;

use App\Forms\Login;

class HomeController extends BaseController
{

    public function SetDefaults() {
        parent::SetDefaults();
    }
    
    
    public function indexAction() 
    {
        $args = array();
        if (!isset($_SESSION['user.id'])) {
            $forms['Login'] = new Login();
            $args['forms'] = $forms;
        }

        $this->render("Home/index", $args);
    }


    public function helloAction($name)
    {
//        phpinfo();
        echo 'hello' . $name;
        $this->StartUserSession("");
    }
    
}

