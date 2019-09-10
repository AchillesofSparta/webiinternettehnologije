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

    class FeatureModel extends Model {

        protected function getFields(): array {
            return [
                "feature_id" => new Field((new NumberValidator())->setIntegerLength(11), false),
                "name"       => new Field((new StringValidator())->setMaxLength(128))
            ];
        }

        public function getById(int $featureid){
            $sql = "SELECT * FROM feature WHERE feature_id = ?;";
            $prep = $this->getConnection()->prepare($sql);
            $res = $prep->execute([$featureid]);
            $feature = NULL;
            if($res){
                $feature = $prep->fetch(PDO::FETCH_OBJ);
            }
            return $feature;
        }

        public function getByName(string $name){
            $sql = "SELECT * FROM feature WHERE name = ?;";
            $prep = $this->getConnection()->prepare($sql);
            $res = $prep->execute([$name]);
            $feature = NULL;
            if($res){
                $feature = $prep->fetch(PDO::FETCH_OBJ);
            }
            return $feature;
        }

        public function getAllByHallId(int $hallId): array {
            $sqlFeatureId = "SELECT * FROM hall_feature where hall_id = ?;";
            $prep = $this->getConnection()->prepare($sqlFeatureId);
            $res = $prep->execute([$hallId]);
            $features = [];
            if($res){
                $features = $prep->fetchAll(PDO::FETCH_OBJ);
            }
            
            return $features;
        }

        
        public function getFeatureNameById(int $id){
            $sql = "SELECT * FROM features WHERE feature_id = ?;";
            $prep2 = $this->getConnection()->prepare($sql);
            $res2 = $prep2->execute([$id]);
            $featureName = NULL;
            if($res2){
                $featureName = $prep2->fetch(PDO::FETCH_OBJ);
            }

            return $featureName;
        }
        

    }