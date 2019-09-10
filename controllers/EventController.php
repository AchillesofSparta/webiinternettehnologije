<?php
    namespace App\Controllers;

    use App\Core\DatabaseConnection;
    use App\Models\AdministratorModel;
    use App\Models\HallModel;
    use App\Core\Model;

    class EventController extends \App\Core\Controller {
        
        public function show($id) {
            $eventModel = new EventModel($this->getDatabaseConnection());
            $event = $eventModel->getById($id);

            if(!$event){
                header("Location: /MB/");
                exit;
            }   

            $this->set("event", $event); 
        }

    }