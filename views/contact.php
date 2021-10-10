<?php

use app\core\form\TextareaField;

$this->title = 'Contact';

?>

<h1>Contact us</h1>

<?php $form = \app\core\form\Form::begin('', 'post') ?>

<?php echo $form->field($model, 'subject') ?>
<?php echo $form->field($model, 'email') ?>
<?php echo new TextareaField($model, 'body') ?>
<div><button type="submit">Send</button></div>

<?php app\core\form\Form::end() ?>