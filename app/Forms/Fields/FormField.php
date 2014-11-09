<?php

namespace App\Forms\Fields;

class FormField
{
    public $id;
    public $name;
    public $value;
    
    public $label;
    public $placeholder;
    public $helptext;

    /**
     * http://documentup.com/Respect/Validation/
     * @var Respect\Validation\Validator 
     */
    public $validator = null;
    /**
     * http://documentup.com/Respect/Validation/#feature-guide/getting-messages
     * @var array
     */
    public $validatorMsgKeys = null;
    
    public $hasError = false;
    public $errorMsg;
    
}