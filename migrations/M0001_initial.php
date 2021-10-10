<?php

    namespace app\migrations;

    class M0001_initial 
    {

        public function up() {
            $db  = \app\core\Application::$app->DB;
            $sql = "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR (255) NOT NULL,
                firstname VARCHAR (255) NOT NULL,
                lastname VARCHAR (255) NOT NULL,
                status TINYINT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            $db->pdo->exec($sql);
        }

        public function down() {
            $db  = \app\core\Application::$app->DB;
            $sql = "DROP TABLE users;";
            $db->pdo->exec($sql);
        }
    }



?>