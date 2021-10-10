<?php

    namespace app\core\DB;

    use app\core\Application;
    use app\migrations\M0001_initial;
    use app\migrations\M0002_add_password_column;

    class Database
    {
        public \PDO $pdo;

        public function __construct(array $config) {
            $dsn      = $config['dsn'];
            $user     = $config['user'];
            $password = $config['password'] ?? '';
            
            $this->pdo = new \PDO($dsn, $user, $password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }

        public function applyMigration() {

            $this->createMigrationTable();
            $appliedMigration = $this->getAppliedMigrations();
            $newMigrations = [];
            $files = scandir(Application::$ROOT_DIR.'/migrations');
            $toapplyMigration = array_diff($files, $appliedMigration);
           
            foreach ($toapplyMigration as $migration) {
                if($migration === '.' || $migration === '..') {
                    continue;
                }

                require_once Application::$ROOT_DIR.'/migrations/'.$migration;
                $className = pathinfo($migration, PATHINFO_FILENAME);
                
                // $instance = new $className();
                $instance1 = new M0001_initial();
                $instance2 = new M0002_add_password_column();


                $this->log("applying migration $migration").PHP_EOL;
                $instance1->up();
                $instance2->up();
                $this->log("applied migration $migration").PHP_EOL;
                $newMigrations[] = $migration;
            }

            if(!empty($newMigrations)) {
                $this->saveMigrations($newMigrations);
            } else {
                $this->log('all migrations are applied');
            }
        
        }

        public function createMigrationTable() {
            $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations(
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255),
                cretaed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
        }

        public function getAppliedMigrations() {
            $statment = $this->pdo->prepare("SELECT migration FROM migrations");
            $statment->execute();
            return $statment->fetchAll(\PDO::FETCH_COLUMN);
        }

        public function saveMigrations(array $migrations) {

            $str = implode(",", array_map(fn($m) => "('$m')", $migrations));

            $statment = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $str");
            $statment->execute();
        }

        protected function log($message) {
            echo '['. date('Y-m-d H:i:s').'] - '.$message. PHP_EOL;
        }

        public function prepare($sql) {
            return $this->pdo->prepare($sql);
        }

    }   

?>