<?php

namespace App\Forms;

use App\Forms\Fields\FormField;
use App\Forms\Fields\MatchingField;
use App\Forms\Fields\SelectOneField;
use App\Forms\Fields\Option;
use Respect\Validation\Validator as v;

class Signup extends Form
{

    function __construct()
    {
        // call the parent constructor to init our fields
        parent::__construct(array(
            'Email',
            'Password',
            'PasswordCheck',
            'FriendlyName'
        ));
    }
    /**
     * @var FormField 
     */
    public $Email;

    protected function initEmail()
    {
        $field = new FormField();
        $field->id = "Signup[Email]";
        $field->name = $field->id;
        $field->label = "Email address";
        $field->placeholder = "genius@example.com";
        $field->helptext = "Enter the email address you plan to use when logging in to this account.";
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
        $field->id = "Signup[Password]";
        $field->name = $field->id;
        $field->label = "Password";
        //$field->placeholder = "Enter a password that makes you feel happy.";
        $field->helptext = "You know the drill. Make it a good one. Anything less than 4 characters and the form get's cranky. "
            ."ProTip: Use a password manager.";
        $field->validator = v::string()->notEmpty()->length(4, null, true);
        $field->validatorMsgKeys = array('string', 'notEmpty', 'length');
        $this->Password = $field;
    }
    /**
     * @var MatchingField 
     */
    public $PasswordCheck;

    protected function initPasswordCheck()
    {
        $field = new MatchingField();
        $field->id = "Signup[PasswordCheck]";
        $field->name = $field->id;
        $field->label = "Password Again";
        //$field->placeholder = "Try entering the same thing you entered the first time.";
        $field->helptext = "Hopefully we'll both remember it now that we've covered it twice.";
        $field->validator = v::string()->notEmpty()->length(4, null, true);
        $field->validatorMsgKeys = array('string', 'notEmpty', 'length');
        $field->sourceFieldId = "Signup[Password]";
        $field->matchPassMsg = "Great! The passwords match.";
        $field->matchFailMsg = "Houston, we have a problem. The passwords aren't the same.";

        $this->PasswordCheck = $field;
    }
    /**
     * @var FormField 
     */
    public $FriendlyName;

    protected function initFriendlyName()
    {
        $field = new FormField();
        $field->id = "Signup[FriendlyName]";
        $field->name = $field->id;
        $field->label = "Name";
        $field->placeholder = "You can call me Al";
        $field->helptext = "What should we call you? (Someday, someone named Betty is going to sign up. I wonder, is today that day?)";
        $field->validator = v::alnum(', . - \' & _')->notEmpty()->length(1, 120,
            true);
        $field->validatorMsgKeys = array('alnum', 'notEmpty', 'length');
        $this->FriendlyName = $field;
    }
}