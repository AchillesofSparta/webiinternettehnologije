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

    class HallModel extends Model {

        protected function getFields(): Array {
            return [
                "hall_id"        => new Field((new NumberValidator())->setIntegerLength(11), false),
                "name"           => new Field((new StringValidator())->setMaxLength(128)),
                "description"    => new Field((new StringValidator())->setMaxLength(255)),
                "is_visible"     => new Field((new BitValidator()))
            ];
        }

        public function getById(int $hallid){
            $sql = "SELECT * FROM hall WHERE hall_id = ?;";
            $prep = $this->getConnection()->prepare($sql);
            $res = $prep->execute([$hallid]);
            $user = NULL;
            if($res){
                $user = $prep->fetch(PDO::FETCH_OBJ);
            }
            return $user;
        }

        public function getByName(string $name){
            $sql = "SELECT * FROM hall WHERE name = ?;";
            $prep = $this->getConnection()->prepare($sql);
            $res = $prep->execute([$name]);
            $hall = NULL;
            if($res){
                $hall = $prep->fetch(PDO::FETCH_OBJ);
            }
            return $hall;
        }

        public function getAllBySearch(string $keywords) {
            # Direct input search (search button on the main page)
            $sql = "SELECT * FROM hall WHERE name LIKE ? OR description LIKE ?;";

            $keywords = "%" . $keywords . "%";

            $prep = $this->getConnection()->prepare($sql);
            if(!$prep){
                return [];
            }
            
            $res = $prep->execute([$keywords, $keywords]);
            if(!$res){
                return [];
            }

            return $prep->fetchAll(\PDO::FETCH_OBJ);
        }

        public function getAll(): array {
            $sql = "SELECT * FROM hall;";
            $prep = $this->getConnection()->prepare($sql);
            $res = $prep->execute();
            $halls = [];
            if($res){
                $halls = $prep->fetchAll(PDO::FETCH_OBJ);
            }
            return $halls;
        }

    }