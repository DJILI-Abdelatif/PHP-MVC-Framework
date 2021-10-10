<?php

    namespace app\core\DB;

    use app\core\Model;
    use app\core\Application;

    abstract class DbModel extends Model
    {

        abstract public function tableName(): string;

        abstract public function attributes(): array;

        abstract public function primaryKey(): string;

        public function save() {
            $tableName = $this->tableName();
            $attributes = $this->attributes();

            $params = array_map(fn($attr) => ":$attr", $attributes);
            $statment = self::prepare("INSERT INTO $tableName(".implode(',', $attributes).")
                                        VALUES(".implode(',', $params).")");
            foreach($attributes as $attribute) {
                $statment->bindParam(":$attribute", $this->{$attribute});
            }
            $statment->execute();
            return true;

        }

        public static function prepare($sql) {
            return Application::$app->DB->pdo->prepare($sql);
        }

        public function findOne($where) {
            $tableName = static::tableName();
            $attributes = array_keys($where);
            // SELECT * FROM $tableName WHERE email = :email AND password = :password;
            $sql = implode("AND", array_map(fn($attr) => "$attr = :$attr", $attributes));
            $statment = self::prepare("SELECT * FROM $tableName WHERE $sql");
            foreach($where as $key => $item) {
                $statment->bindValue(":$key", $item);
            }
            $statment->execute();
            return $statment->fetchObject(static::class);
        }

    }



?>