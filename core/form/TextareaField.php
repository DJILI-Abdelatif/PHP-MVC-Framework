<?php

    namespace app\core\form;

    class TextareaField extends BaseField
    {
        public function renderInput(): string
        {
            return sprintf('<textarea name="%s" class="form-control %s">%s</textarea>',
                $this->attribute,
                $this->model->hasErrors($this->attribute ? 'is-invalide' : ''),
                $this->model->{$this->attribute} 
            );
        }
    }

?>