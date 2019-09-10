<?php
    namespace App\Models;
    use App\Core\DatabaseConnection;
    use App\Core\Model;
    use App\Core\Field;
    use App\Validators\NumberValidator;
    use App\Validators\StringValidator;
    use App\Validators\DateTimeValidator;
    use App\Validators\BitValidator;
    use \PDO;

    class AdministratorModel extends Model {

        protected function getFields(): array {
            return [
                "email"            => new Field((new StringValidator())->setMaxLength(128), true),
                "password"         => new Field((new StringValidator())->setMaxLength(128), true)
            ];
        }

        public function getById(int $userId){
            $sql = "SELECT * FROM administrator WHERE administrator_id = ?;";
            $prep = $this->getConnection()->prepare($sql);
            $res = $prep->execute([$userId]);
            $user = NULL;
            if($res){
                $user = $prep->fetch(PDO::FETCH_OBJ);
            }
            return $user;
        }

        public function getAll(): array {
            $sql = "SELECT * FROM administrator;";
            $prep = $this->getConnection()->prepare($sql);
            $res = $prep->execute();
            $users = [];
            if($res){
                $users = $prep->fetchAll(PDO::FETCH_OBJ);
            }
            return $users;
        }

        public function getByUsername(string $username){
            $sql = "SELECT * FROM administrator WHERE email = ?;";
            $prep = $this->getConnection()->prepare($sql);
            $res = $prep->execute([$username]);
            $user = NULL;
            if($res){
                $user = $prep->fetch(PDO::FETCH_OBJ);
            }
            return $user;
        }


    }