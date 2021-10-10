<?php
/** @var $model \app\models\User */

    $this->title = 'Register';

?>

<h1>Create account</h1>

<?php $form = app\core\form\Form::begin('', 'post');?>

    <?php echo $form->field($model, 'firstname'); ?>
    <?php echo $form->field($model, 'lastname'); ?>
    <?php echo $form->field($model, 'email'); ?>
    <?php echo $form->field($model, 'password')->passwordField(); ?>
    <?php echo $form->field($model, 'confirmpassword')->passwordField(); ?>
    <button type="submit">Sing in</button>

<?php echo app\core\form\Form::end();?>
