<?php
/**
 * Created by PhpStorm.
 * User: Paulius
 * Date: 16/12/2017
 * Time: 23:53
 */

class BaseModel
{
    protected $errors = array();
    protected $validationRules = array();
    protected $fieldLabels = array();
    protected $table;
    protected $dopDownValueLabels;

    protected function validate($record) {
        foreach ($this->validationRules as $ruleArray) {
            $fields = array_map('trim', explode(',', $ruleArray[0]));
            $ruleName = $ruleArray[1];
            $ruleValue = isset($ruleArray[2]) ? $ruleArray[2] : null;

            switch ($ruleName){
                case 'required':
                    foreach ($fields as $field) {
                        if (isset($record[$field]) && empty($record[$field])) {
                            $this->addError($field, "Field '{$this->fieldLabels[$field]}' cannot be empty.");
                        }
                    }
                    break;
                case 'email':
                    foreach ($fields as $field) {
                        if (isset($record[$field]) && !filter_var($record[$field], FILTER_VALIDATE_EMAIL)) {
                            $this->addError($field, 'Invalid email provided.');
                        }
                    }
                    break;
                case 'maxLength':
                    foreach ($fields as $field) {
                        if (isset($record[$field]) && strlen($record[$field]) > $ruleValue) {
                            $this->addError($field, "Field '{$this->fieldLabels[$field]}' is too long.");
                        }
                    }
                    break;
                case 'unique':
                    foreach ($fields as $field) {
                        if (isset($record[$field])) {
                            $existingRecord = DB::queryOne($this->table, array($field => $record[$field]));
                            if (!empty($existingRecord)) {
                                $this->addError($field, "'{$this->fieldLabels[$field]}' already exists in the system.");
                            }
                        }
                    }
                    break;
                case 'price':
                    foreach ($fields as $field) {
                        if (isset($record[$field]) && preg_match('/^\d+(?:\.\d{2})?$/', $record[$field]) == '0') {
                            $this->addError($field, "Is not a price format.");
                        }
                    }
                    break;
                case 'postcode':
                    foreach ($fields as $field) {
                        if (isset($record[$field]) && Core::check_postcode_uk($record[$field]) === false) {
                            $this->addError($field, "Invalid postcode.");
                        }
                    }
                    break;
            }
        }
        return empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function addError($field, $error)
    {
        if (empty($this->errors[$field])) {
            $this->errors[$field] = $error;
        }
    }

    static public function getDropDownValueLabel($dropDown, $value) {
        $self = new static();
        return $self->dopDownValueLabels[$dropDown][$value];
    }

    static public function getAllDropDownLabels() {
        $self = new static();
        return $self->dopDownValueLabels;
    }
}