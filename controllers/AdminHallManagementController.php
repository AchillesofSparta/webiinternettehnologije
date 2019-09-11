<?php
    namespace App\Controllers;

    use App\Models\AdministratorModel;
    use App\Models\HallModel;
    use App\Models\FeatureModel;
    use App\Models\HallFeatureModel;

    class AdminHallManagementController extends \App\Core\Role\UserRoleController {
        public function halls() {
            // Get Hall data to display
            $hallModel = new HallModel($this->getDatabaseConnection());
            $halls = $hallModel->getAll();
            $this->set("halls", $halls);
        }

        public function getEdit($hallId) {

            $hallModel = new HallModel($this->getDatabaseConnection());
            $hall = $hallModel->getById($hallId);

            if(!$hall) {
                $this->redirect(\Configuration::BASE . "administrator/halls");
            }

            # Set View-u
            $this->set("hall", $hall);

            return $hallModel;
            
        }

        public function postEdit($hallId) {
            # Recikliraj metod odozgo
            $hallModel = $this->getEdit($hallId);

            $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
            $description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_STRING);

            $hallModel->editById($hallId, [
                "name" => $name,
                "description" => $description
            ],"hall");

            # Proveri da li je slika uopste prosledjena u formularu
            if(isset($_FILES["image"]) && $_FILES["image"]["error"] == 0){
                $uploadStatus = $this->doImageUpload("image", $hallId);
                    if(!$uploadStatus){
                        $this->set("message", "Hall has been edited but an image has not been uploaded...");
                        return;
                    }
            }
            

            $this->redirect(\Configuration::BASE . "administrator/halls");
        }

        public function getAdd() {

        }

        public function postAdd() {
            $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
            $description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_STRING);

            $seats = filter_input(INPUT_POST, "seats", FILTER_SANITIZE_STRING);
            $type = filter_input(INPUT_POST, "type", FILTER_SANITIZE_STRING);
            $wifi = isset($_POST['wifi']);
            $rostrum = isset($_POST['rostrum']);
            $surroundSound = isset($_POST['surroundSound']);
            $projector = isset($_POST['projector']);

            $hallModel = new HallModel($this->getDatabaseConnection());
            
            # First - Add a new Hall
            $hallId = $hallModel->add([
                "name" => $name,
                "description" => $description
            ], "hall");
            

            # Second - if Add was successful - add Features
            if($hallId) {
            
                $getNewHall = $hallModel->getByName($name);
                $newHallId = $getNewHall->hall_id;

                # Add selected features one by one (seats and type are mandatory, others are optional)
                $hallFeatureModel = new hallFeatureModel($this->getDatabaseConnection());
                
                # MANDATORY
                # ADD SEATS
                $hallFeatureModelId = $hallFeatureModel->add([
                    "hall_id"       => $newHallId,
                    "feature_id"    => 1,
                    "value"         => $seats
                ], "hall_feature");

                
                # ADD TYPE
                $hallFeatureModelId = $hallFeatureModel->add([
                    "hall_id"       => $newHallId,
                    "feature_id"    => 6,
                    "value"         => $type
                ], "hall_feature");

                # OPTIONAL
                if($wifi==1){
                    # ADD WIFI
                    echo "DOING WIFI";
                    $hallFeatureModelId = $hallFeatureModel->add([
                        "hall_id"       => $newHallId,
                        "feature_id"    => 2,
                    ], "hall_feature");
                }

                if($rostrum==1){
                    # ADD ROSTRUM
                    $hallFeatureModelId = $hallFeatureModel->add([
                        "hall_id"       => $newHallId,
                        "feature_id"    => 3,
                    ], "hall_feature");
                }

                if($surroundSound==1){
                    # ADD SURROUNDSOUND
                    $hallFeatureModelId = $hallFeatureModel->add([
                        "hall_id"       => $newHallId,
                        "feature_id"    => 4,
                    ], "hall_feature");
                }

                if($projector==1){
                    # ADD PROJECTOR
                    $hallFeatureModelId = $hallFeatureModel->add([
                        "hall_id"       => $newHallId,
                        "feature_id"    => 5,
                    ], "hall_feature");
                }

                $uploadStatus = $this->doImageUpload("image", $hallId);
                if(!$uploadStatus){
                    $this->set("message", "Hall has been added but an image has not been uploaded...");
                    return;
                }

                $this->redirect(\Configuration::BASE . "administrator/halls");
            }

            $this->set("message", "Error. Failed adding a new Hall...");

            
        }

        // File upload prep
        private function doImageUpload(string $fieldName, string $fileName): bool {
            # Check and remove if an image already exists first
            unlink(\Configuration::UPLOAD_DIR . $fileName . ".jpg");

            $uploadPath = new \Upload\Storage\FileSystem(\Configuration::UPLOAD_DIR);
            
            # Prep the file
            $file = new \Upload\File($fieldName, $uploadPath);
            $file->setName($fileName);
            
            /*
            $file->addValidations([
                new \Upload\Validation\Mimetype("image/jpeg"),
                new \Upload\Validation\Size("20m")
            ]);*/

            try {
                $file->upload();
                return true;
            } catch (Exception $e) {
                $this->set("message","Error: " . implode(", ", $file->getErrors()));
                return false;
            }
        }



    }