<?php

namespace App\Forms;

use Respect\Validation\Validator as v;


class Form
{
    protected $fields;
    public $IsValid;
    
    function __construct(array $fields) 
    {
        $this->fields = $fields;
        
        foreach ($this->fields as $field)
        {
            $initFunctionName = 'init'.$field;
            if (method_exists($this, $initFunctionName)){
                $this->$initFunctionName();
            }
        }
        
    }

    
    public function getFields()
    {
        $fieldList = array();
        foreach ($this->fields as $field)
        {
            $fieldList[$field] = $this->$field;
        }
        return $fieldList;
    }

    
    public static function getFromPostData(array $postdata, array $constructorParams = null)
    {
        $instance = $constructorParams === null
            ? new static()
            : new static($constructorParams);
        $instance->IsValid = true; //assume we're good
        foreach ($instance->fields as $fieldname)
        {
            $postedValue = $postdata[$fieldname];

            $field = $instance->$fieldname;
            $field->value = $postedValue;
            if (isset($field->validator)){
                try {
                    $field->validator->assert($postedValue);
                } catch(\InvalidArgumentException $e) {
                    $field->hasError = true;
                    $instance->IsValid = false;
                    $field->errorMsg = $instance->cleanupRespectMessages(
                            $e->findMessages($field->validatorMsgKeys),
                            $field->label,
                            $postedValue
                            );
//                    var_dump($e->findMessages($field->validatorMsgKeys));
//                    var_dump($e);
                }
            }
        }
    	return $instance;
    }   
    
    // https://github.com/Respect/Validation/issues/86
    protected function cleanupRespectMessages(array $original, $label, $postedValue)
    {

        $clean = array();
        foreach ($original as $key => $value) 
        {
            if (empty($value)) { continue; }
            
            $clean[$key] = str_replace('"'.$postedValue.'"', $label, $value);
        }
        
        return $clean;
        
    }
    
}
