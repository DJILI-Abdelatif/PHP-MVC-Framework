<?php

    namespace app\core\form;

    use app\core\Model;

    class Form 
    {

        public function begin($action, $method) {
            echo sprintf('<form action="%s" method="%s">', $action, $method);
            return new Form();
        }      

        public function end() {
            echo '</form>';
        }

        public function field(Model $model, $attribute) {
            return new InputField($model, $attribute);
        }
    }


?>