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
                echo "ABOUT TO MAKE SEATS";
                # MANDATORY
                # ADD SEATS
                $hallFeatureModelId = $hallFeatureModel->add([
                    "hall_id"       => $newHallId,
                    "feature_id"    => 1,
                    "value"         => $seats
                ], "hall_feature");

                echo "ABOUT TO MAKE TYPE";
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

                $this->redirect(\Configuration::BASE . "administrator/halls");
            }

            $this->set("message", "Error. Failed adding a new Hall...");

            
        }
    }