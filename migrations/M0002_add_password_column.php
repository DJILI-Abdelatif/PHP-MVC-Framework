<?php

    namespace app\migrations;

    class M0002_add_password_column
    {
        
        public function up() {
            $db = \app\core\Application::$app->DB;
            $db->pdo->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS password VARCHAR (255) NOT NULL");
        }

        public function down() {
            $db = \app\core\Application::$app->DB;
            $db->pdo->exec("ALTER TABLE users DROP COLUMN password");
        }
    }



?>