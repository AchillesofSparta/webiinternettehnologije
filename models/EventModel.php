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

    class EventModel extends Model {

        protected function getFields(): array {
            return [
                "hall_id"            => new Field((new NumberValidator())->setIntegerLength(11), true),
                "type_id"            => new Field((new NumberValidator())->setIntegerLength(11), true),
                "administrator_id"   => new Field((new NumberValidator())->setIntegerLength(11), true),
                "date"               => new Field((new DateTimeValidator())->disallowTime()->allowDate()),
                "name"               => new Field((new StringValidator())->setMaxLength(255))
            ];
        }

        // Unnecessary for now
        public function getById(int $featureid){
            $sql = "SELECT * FROM event WHERE event_id = ?;";
            $prep = $this->getConnection()->prepare($sql);
            $res = $prep->execute([$featureid]);
            $feature = NULL;
            if($res){
                $feature = $prep->fetch(PDO::FETCH_OBJ);
            }
            return $feature;
        }

        public function getAllByHallId(int $hallId): array {
            $sqlFeatureId = "SELECT * FROM event where hall_id = ?;";
            $prep = $this->getConnection()->prepare($sqlFeatureId);
            $res = $prep->execute([$hallId]);
            $features = [];
            if($res){
                $features = $prep->fetchAll(PDO::FETCH_OBJ);
            }
            
            return $features;
        }

        # Most important
        public function getByDate(string $date){
            $sql = "SELECT * FROM event WHERE date = ?;";
            $prep2 = $this->getConnection()->prepare($sql);
            $res2 = $prep2->execute([$date]);
            $featureName = NULL;
            if($res2){
                $featureName = $prep2->fetch(PDO::FETCH_OBJ);
            }

            return $featureName;
        }

        public function getByDateAndHallId(string $date, int $hallId){
            $sql = "SELECT * FROM event WHERE date = ? AND hall_id = ?;";
            $prep2 = $this->getConnection()->prepare($sql);
            $res2 = $prep2->execute([$date, $hallId]);
            $featureName = NULL;
            if($res2){
                $featureName = $prep2->fetch(PDO::FETCH_OBJ);
            }

            return $featureName;
        }

        // Read only event_type table
        public function getAllEventTypes(){
            $sqlFeatureId = "SELECT * FROM event_type";
            $prep = $this->getConnection()->prepare($sqlFeatureId);
            $res = $prep->execute();
            $features = [];
            if($res){
                $features = $prep->fetchAll(PDO::FETCH_OBJ);
            }
            
            return $features;
        }
        

    }