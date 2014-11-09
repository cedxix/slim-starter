<?php

namespace App\Forms;

use App\Forms\Fields\FormField;
use Respect\Validation\Validator as v;


class Login extends Form
{
    function __construct() 
    {
        // call the parent constructor to init our fields
        parent::__construct(array(
            'Email', 
            'Password'
            ));
    }
    
    /**
     * @var FormField 
     */
    public $Email;
    protected function initEmail()
    {
        $field = new FormField();
        $field->id = "Login[Email]";
        $field->name = $field->id;
        $field->label = "Email address";
        $field->placeholder = "email@example.com";
        $field->validator = v::email()->notEmpty();
        $field->validatorMsgKeys = array('email', 'notEmpty');
        $this->Email = $field;
    }

    /**
     * @var FormField 
     */
    public $Password;
    protected function initPassword()
    {
        $field = new FormField();
        $field->id = "Login[Password]";
        $field->name = $field->id;
        $field->label = "Password";
        $field->placeholder = "tr1cky.Pa55w0rd";
        $field->validator = v::string()->notEmpty()->length(4, null, true);
        $field->validatorMsgKeys = array('string', 'notEmpty', 'length');
        $this->Password = $field;
    }

    
}


