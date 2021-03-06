<?php

    namespace app\core;

    abstract class Model
    {
        public const RULE_REQUIRED = 'required';
        public const RULE_EMAIL = 'email';
        public const RULE_MAX = 'max';
        public const RULE_MIN = 'min';
        public const RULE_MATCH = 'match';
        public const RULE_UNIQUE = 'unique';

        public function loadData($data) {
            foreach ($data as $key => $value) {
                if(property_exists($this, $key)) {
                    $this->{$key} = $value;
                }
            }
        }
        
        abstract public function rules(): array;
        
        public function labels(): array {
            return [];
        }

        public function getLabel($attribute) {
            return $this->labels()[$attribute] ?? $attribute;
        }

        public array $errors = [];

        public function validate() {
            foreach ($this->rules() as $attribute => $rules) {
                $value = $this->{$attribute};
                foreach($rules as $rule) {
                    $rulename = $rule;
                    if(!is_string($rulename)) {
                        $rulename = $rule[0];
                    } 
                    if($rulename === self::RULE_REQUIRED && !$value) {
                        $this->addErrorForRule($attribute, self::RULE_REQUIRED);
                    }
                    if($rulename === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $this->addErrorForRule($attribute, self::RULE_EMAIL);
                    }
                    if($rulename === self::RULE_MIN && strlen($value) < $rule['min']) {
                        $this->addErrorForRule($attribute, self::RULE_MIN, $rule);
                    }
                    if($rulename === self::RULE_MAX && strlen($value) > $rule['max']) {
                        $this->addErrorForRule($attribute, self::RULE_MAX, $rule);
                    }
                    if($rulename === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
                        $rule['match'] = $this->getLabel($rule['match']);
                        $this->addErrorForRule($attribute, self::RULE_MATCH, $rule);
                    }
                    if($rulename === self::RULE_UNIQUE) {
                        $className  = $rule['class'];
                        $uniqueAttr = $rule['attribute'] ?? $attribute;
                        $tableName  = $className::tableName();
                        $statement = Application::$app->DB->prepare("SELECT * FROM $tableName WHERE $uniqueAttr = :attr");
                        $statement->bindValue(":attr", $value);
                        $statement->execute();
                        $record = $statement->fetchObject();
                        if($record) {
                            $this->addErrorForRule($attribute, self::RULE_UNIQUE, ['field' => $this->getLabel($attribute)]);
                        }
                    }
                }
            }
            return empty($this->errors);
        }

        private function addErrorForRule(string $attribute, string $rule, $params = []) {
            $message = $this->errorMessages()[$rule] ?? '';
            foreach ($params as $key => $value) {
                $message = str_replace("{{$key}}", $value, $message);
            }
            $this->errors[$attribute][] = $message;
        }

        public function addError(string $attribute, string $message) {
            $this->errors[$attribute][] = $message;
        }

        public function errorMessages() {
            return [
                self::RULE_REQUIRED => 'this feild is required',
                self::RULE_EMAIL    => 'this feild must be valide address',
                self::RULE_MAX      => 'max length of this feild must be {max}',
                self::RULE_MIN      => 'min length of this feild must be {min}',
                self::RULE_MATCH    => 'this feild must be the same as {match}',
                self::RULE_UNIQUE   => 'record with this {field} already exists'
            ];
        }

        public function hasErrors($attribute) {
            return $this->errors[$attribute] ?? false;
        } 

        public function getFirstError($attribute) {
            return $this->errors[$attribute][0] ?? false;
        }


    }



?>