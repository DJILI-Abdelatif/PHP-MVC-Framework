<?php

    use app\core\Application;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->title ?></title>
</head>
<body>
    
    <strong>this is the main layout</strong><br>

    
    <a href="/">Home</a>
    <a href="/contact">Contact</a>
    <?php if (Application::isGuest()): ?>
        <a href="/register">Register</a>
    <a href="/login">Login</a>
    <?php else: ?>
    <a href="/profile">Profile</a>
    <h4>Welcom <?php echo Application::$app->user->getDisplayingName(); ?><a href="/logout"> (Logout) </a></h4>
    <?php endif; ?>
    <?php
        if(Application::$app->session->getFlash('success')) {
            echo Application::$app->session->getFlash('success');
        }
    ?>

    {{content}}

</body>
</html>