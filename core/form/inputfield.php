<?php

    namespace app\core\form;

    use app\core\Model;

    class InputField extends BaseField
    {
        public const TYPE_TEXT = 'text';
        public const TYPE_PASSWORD = 'password';
        public const TYPE_NUMBER = 'number';

        public string $type;

        public function __construct(Model $model, $attribute)
        {
            $this->type = self::TYPE_TEXT;
            parent::__construct($model, $attribute);
        }

        public function passwordField() {
            $this->type = self::TYPE_PASSWORD;
            return $this;
        }

        public function renderInput(): string
        {
            return sprintf('<input type="%s" name="%s" value="%s" class="%s">',
            $this->type,
            $this->attribute, 
            $this->{$this->attribute},
            $this->model->hasErrors($this->attribute) ? 'is-invalid' : ''
            // $this->model->hasErrors('firstname') ? 'is-invalide' : ''
            );
        }

    }


?>