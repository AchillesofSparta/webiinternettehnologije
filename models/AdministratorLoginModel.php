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

    class AdministratorLoginModel extends Model {
        protected function getFields(): array {
            return [
                "administrator_id" => new Field((new NumberValidator())->setIntegerLength(11), false),
                "ip_address" => Field::editableIpAddress()
            ];
        }

        public function getAllByAdministratorId(int $administratorId): array {
            $sqlFeatureId = "SELECT * FROM administrator_login where administrator_id = ?;";
            $prep = $this->getConnection()->prepare($sqlFeatureId);
            $res = $prep->execute([$administratorId]);
            $admin = [];
            if($res){
                $admin = $prep->fetchAll(PDO::FETCH_OBJ);
            }
            
            return $admin;
        }

        public function getAllByIpAddress(string $ipAddress): array {
            $sqlFeatureId = "SELECT * FROM administrator_login where ip_address = ?;";
            $prep = $this->getConnection()->prepare($sqlFeatureId);
            $res = $prep->execute([$ipAddress]);
            $ip = [];
            if($res){
                $ip = $prep->fetchAll(PDO::FETCH_OBJ);
            }
            
            return $ip;
        }

    }