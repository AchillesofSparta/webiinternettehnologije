<?php
    namespace App\Controllers;

    use App\Models\AdministratorModel;

    class AdminDashboardController extends \App\Core\Role\UserRoleController {
        public function index() {

        }

        public function getRegister() { }

        # Send admin register info
        public function postRegister() {
            $email = filter_input(INPUT_POST, "reg_email", FILTER_SANITIZE_EMAIL);
            $password1 = filter_input(INPUT_POST, "reg_password_1", FILTER_SANITIZE_STRING);
            $password2 = filter_input(INPUT_POST, "reg_password_2", FILTER_SANITIZE_STRING);

            if($password1 !== $password2) {
                $this->set("message", "Passwords mismatch!");
                return;
            }

            if(!(new \App\Validators\StringValidator())->setMinLength(8)->setMaxLength(128)->isValid($password1)){
                $this->set("message", "Password format is not correct! Check password length...");
                return;
            }

            # Since the username is an email, this check is reduntant but a good check nonetheless
            $adminModel = new AdministratorModel($this->getDatabaseConnection());
            $user = $adminModel->getByUsername($email);
            if($user) {
                $this->set("message", "User with this email already exists...");
                return;
            }

            # Generate pwd hash
            $passwordHash = \password_hash($password1, PASSWORD_DEFAULT);

            $userId = $adminModel->add([
                "email" => $email,
                "password" => $passwordHash
            ], "administrator");

            if(!$userId){
                $this->set("message", "User error! User registration was not successful...");
                return;
            }

            $this->set("message", "You have successfully registered a new administrator!");
        }

    }