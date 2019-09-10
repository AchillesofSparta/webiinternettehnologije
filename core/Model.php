<?php  
    namespace App\Core;

    abstract class Model {
        private $dbc;

        final public function __construct(DatabaseConnection &$dbc) {
            $this->dbc = $dbc;
        }

        final protected function getConnection() {
            return $this->dbc->getConnection();
        }

        
        protected function getFields(): array {
            return [ ];
        }
        
        final private function checkFieldList(array $data){
            $fields = $this->getFields();
            $supportedFieldNames = array_keys($fields);
            $requestedFieldNames = array_keys($data);

            # String tableName sluzi za proveru odgovarajuce tabele: admin, event ili hall
            # Provera da li polja postoje u bazi
            # Provera da li je polje editable
            # Provera da li je vrednost u redu

            foreach ( $requestedFieldNames as $requestedFieldName ){
                if ( !in_array($requestedFieldName, $supportedFieldNames)){
                    throw new \Exception("Field " . $requestedFieldName . " is not supported.");
                }

                if( !$fields[$requestedFieldName]->isEditable()){
                    throw new \Exception("Field " . $requestedFieldName . " is not editable.");
                }

                if( !$fields[$requestedFieldName]->isValid($data[$requestedFieldName])){
                    throw new \Exception("Field value of " . $requestedFieldName . " is not valid.");
                }
            }
        }

        // MIGHT NEED LATER
        final private function getTableName(): string {
            $matches = [];
            preg_match("|^.*\\\((?:[A-Z][a-z]+)+)Model$|", static::class, $matches);
            return substr(strtolower(preg_replace("|[A-Z]|", "_$0", $matches[1] ?? "")), 1);
        }
        
        /* OLD FUNCTION
        final private function isFieldNameValid(string $filedName) {
            return boolval(preg_match("|^[a-z][a-z_0-9]+[a-z0-9]$|", $fieldName));
        }
        */

        final private function isFieldValueValid(string $filedName, $fieldValue): bool {
            $fields = $this->getFields();
            $supportedFieldNames = array_keys($fields);

            if(!in_array($fieldName, $supportedFieldNames)){
                return fase;
            }

            return $fields[$fieldName]->isValid($fieldValue);
        }


        // MIGHT NEED LATER
        final public function getByFieldName(string $filedName, $value) {
            if(!$this->isFieldValueValid($fieldName, $value)) {
                throw new Exception("Invalid field name or value: " . $fieldName);
            }

            $tableName = $this->getTableName();
            $sql = "SELECT * FROM " .  $tableName . " WHERE " . $fieldName . " =?;";
            $prep = $this->dbc->getConnection()->prepare($sql);
            $result = $prep->execute([$value]);
            $item = NULL;
            if($result){
                $item = $prep->fetch(\PDO::FETCH_OBJ);
            }

            return $item;
        }
        

        final public function add(array $data, string $tableName) {
            $this->checkFieldList($data);

            $sqlFiledNames = implode(", ", array_keys($data));
            $questionMarks = str_repeat("?,", count($data));
            $questionMarks = substr($questionMarks, 0, -1);
            $sql = "INSERT INTO {$tableName} ({$sqlFiledNames}) VALUES ({$questionMarks});";
            $prep = $this->getConnection()->prepare($sql);
            $result = $prep->execute(array_values($data));

            # provera izvrsenja query-ja
            if(!$result) {
                return false;
            }

            return $this->dbc->getConnection()->lastInsertId();

        }  
        
        final public function editById(int $id, array $data, string $tableName) {
            $this->checkFieldList($data);
           
            $editList = [];
            $values = [];
            foreach($data as $fieldName => $value){
                $editList[] = "{$fieldName} = ?";
                $values[] = $value;
            }

            $editString = implode(", ", $editList);
            $values[] = $id;

            $sql = "UPDATE {$tableName} SET {$editString} WHERE {$tableName}_id = ?;";
            $prep = $this->dbc->getConnection()->prepare($sql);
            return $prep->execute($values);
        }


        final public function deleteById(int $id, string $tableName){
            $tableName = $this->getTableName();
            $sql = "DELETE * FROM ' . $tableName . ' WHERE ' . $tableName . '_id = ?;";
            $prep = $this->dbc->getConnection()->prepare($sql);
            return $prep->execute([$k]);
        }

        
    }
        

    
    







        /* CANNOT USE DUE TO TOO MANY DIFFERENCES
        Class Model intended for:
            - database connection
            - model constructors
            - additional add/modify/delete functions


        final private function getTableName(): string {
            # in case of retrieving App\Core\ExampleModel
            $matches = [];
            preg_match('|^.*\\\((?:[A-Z][a-z]+)+)Model$|', static::class, $matches);
            $className = $matches[1] ?? '';
            # converting the class name to underscore
            $underscoredClassName = preg_replace('|[A-Z]|','_$0', $className);
            # converting the class name to lower case
            $lowerCaseUnderscoredClassName = strtolower($underscoredClassName);
            # return string after first char ie the correct class name
            return substr($lowerCaseUnderscoredClassName, 1);
        }        

        final public function getById(int $id){
            $tableName = $this->getTableName();
            $sql = "SELECT * FROM ' . $tableName . ' WHERE ' . $tableName . '_id = ?;";
            $prep = $this->dbc->getConnection()->prepare($sql);
            $res = $prep->execute([$k]);
            $item = NULL;
            if($res){
                $item = $prep->fetch(PDO::FETCH_OBJ);
            }
            return $item;
        }

        final public function getByName(string $name){
            $tableName = $this->getTableName();
            $sql = "SELECT * FROM ' . $tableName . ' WHERE name = ?;";
            $prep = $this->dbc->getConnection()->prepare($sql);
            $res = $prep->execute([$name]);
            $item = NULL;
            if($res){
                $item = $prep->fetch(PDO::FETCH_OBJ);
            }
            return $item;
        }

        final public function getAll(): array {
            $tableName = $this->getTableName();
            $sql = "SELECT * FROM ' . $tableName . ';";
            $prep = $this->dbc->getConnection()->prepare($sql);
            $res = $prep->execute();
            $item = [];
            if($res){
                $item = $prep->fetchAll(PDO::FETCH_OBJ);
            }
            return $item;
        }
        */
    