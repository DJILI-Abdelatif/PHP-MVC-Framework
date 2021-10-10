<?php

    namespace app\core\form;

    use app\core\Model;

    abstract class BaseField 
    {
        public Model $model;
        public string $attribute;

        public function __construct(Model $model, $attribute)
        {
            $this->model     = $model;
            $this->attribute = $attribute;
        }

        abstract public function renderInput(): string;

        public function __toString()
        {
            return sprintf('
                <div>
                    <label>%s</label>
                        %s
                    <div class="feed-back">
                        %s
                    </div>
                </div>
            ', 
            $this->model->getLabel($this->attribute),
            $this->renderInput(),
            $this->model->getFirstError($this->attribute));
        }

    }

?>