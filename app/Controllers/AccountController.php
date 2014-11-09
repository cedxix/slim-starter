<?php

namespace App\Controllers;

use App\Forms\Signup;
use App\Forms\Login;


class AccountController extends BaseController
{
    public function signupAction() 
    {
        if ($this->app->request()->isPost()) {
            try {
                $signup = Signup::getFromPostData($this->app->request->params('Signup'));
                if ($signup->IsValid) {

                    // create their account
//                    $user = User::CreateUserFromSignup($signup);

                    // sign them in (create session)
//                    $this->StartUserSession($user);
                    $this->StartUserSession();

                    // set a flash success message
                    $this->app->flash('success', 'Congratulations! Signup successful.');

                    // redirect to create a beta
                    $this->redirect('/profile');
                }

                // Oops! we must have an error..
                $this->app->flash('error', 'Oops! Something appears to be wrong with your signup information.');
                $forms['Signup'] = $signup;
                $this->render("Account/Signup", array('forms' => $forms));

            } catch (\Exception $e) {
                //todo: figure out how we want to handle this stuff
                $this->app->log->error($this->PackE($e));
                //var_dump($e);
            }            
        } else {
            $forms['Signup'] = new Signup();
            $this->render("Account/Signup", array('forms' => $forms));
        }
    }
    

    
    public function loginAction() 
    {

        if ($this->app->request()->isPost()) {
            try {
                
                $login = Login::getFromPostData($this->app->request->params('Login'));

                if ($login->IsValid) {
//                    $user = User::AttemptLogin($login);
                    
                    // sign them in (create session)
//                    $this->StartUserSession($user);
                    $this->StartUserSession($user);

                    $redirectPath = isset($_SESSION['urlRedirect'])
                            ? $_SESSION['urlRedirect']
                            : '/profile';
                    
                    unset($_SESSION['urlRedirect']);

                    $this->redirect($redirectPath);
                }

                // Oops! we must have an error..
                $this->app->flash('error', 'Oops! Something appears to be wrong with your login information.');
                $forms['Login'] = $login;
                $this->render("Account/Login", array('forms' => $forms));

            } catch (\Exception $e) {
                //todo: figure out how we want to handle this stuff
                var_dump($e);
                echo "<div>"
                . "Error..."
                . " code=" . ($e->getCode() == -1) ? -1 : 0
                . " message=" . $e->getMessage()
                . "</div>";
            }            
            
        } else {
            $forms['Login'] = new Login();
            $this->render("Account/Login", array('forms' => $forms));
        }
    }

    
    public function logoutAction() 
    {
        $this->KillUserSession();
        $this->redirect('/');
    }
    
    public function profileAction() 
    {
        $this->render("Account/Profile", array(
            'NavTag' => 'profile'
        ));
    }



}

