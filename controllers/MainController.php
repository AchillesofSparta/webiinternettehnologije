<?php
    namespace App\Controllers;

    use App\Core\DatabaseConnection;
    use App\Models\AdministratorModel;
    use App\Models\HallModel;
    use App\Models\AdministratorLoginModel;
    use App\Core\Model;

    class MainController extends \App\Core\Controller {
        
        public function home() {
            $hallModel = new HallModel($this->getDatabaseConnection());
            $halls = $hallModel->getAll();

            if(!$halls){
                header("Location: /MB/");
                exit;
            }

            $this->set("halls", $halls);  
            
            #$this->getSession()->put("neki_kljuc", "Neka vrednost" . rand(100, 999));
            #$this->getSession()->save();

            /* DISPLAY SESSION INFO
            $staraVrednost = $this->getSession()->get("brojac", 0);
            $novaVrednost = $staraVrednost + 1;
            $this->getSession()->put("brojac", $novaVrednost);
            $this->set("podatak", $novaVrednost);
            */
            
            /*
            $deleteTest = new App\Models\AdministratorLoginModel($this->getDatabaseConnection);
            $deleteTest->editById(21, [
                "administrator_id" => "5"
            ]);
            */
        }

        # Admin login
        public function getLogin() {

        }

        public function contact() {

        }

        public function aboutUs() {

        }

        public function getLogout() {
            $this->getSession()->remove("user_id");
            $this->getSession()->save();
            $this->redirect(\Configuration::BASE);
        }
        

        public function postLogin(){
            $username = \filter_input(INPUT_POST, "login_username", FILTER_SANITIZE_STRING);
            $password = \filter_input(INPUT_POST, "login_password", FILTER_SANITIZE_STRING);

            $validPassword = (new \App\Validators\StringValidator())->setMinLength(8)->setMaxLength(128)->isValid($password);

            # Password format check
            if(!$validPassword) {
                $this->set("message", "Password format not correct!");
                return;
            }

            # User check
            $adminModel = new AdministratorModel($this->getDatabaseConnection());
            $admin = $adminModel->getByUsername($username);
            
            if(!$admin) {
                $this->set("message", "User non existent...");
                return;
            }
            
            /*
            echo \password_hash($password, PASSWORD_DEFAULT) . " |||";
            echo $admin->password_hash;
            */
            //$newPwd = \password_hash($password, PASSWORD_DEFAULT);
            #$oldPwdField = $admin->getFields();

            if(!password_verify($password, $admin->password)){
                # Slow down bruteforce with a second sleep period
                sleep(1);
                $this->set("message", "Password incorrect...");
                return;
            }
            
            $this->getSession()->put("user_id", $admin->administrator_id);
            $this->getSession()->save();

            $this->redirect(\Configuration::BASE .  "administrator/halls");

        }

        

    }