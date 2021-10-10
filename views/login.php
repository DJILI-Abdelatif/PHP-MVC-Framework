<?php
/** @var $model \app\models\User */

    $this->title = 'Login';
?>

<h1>Login</h1>

<?php $form = app\core\form\Form::begin('', 'post');?>

    <?php echo $form->field($model, 'email'); ?>
    <?php echo $form->field($model, 'password')->passwordField(); ?>
    <button type="submit">Sing in</button>

<?php echo app\core\form\Form::end();?>

