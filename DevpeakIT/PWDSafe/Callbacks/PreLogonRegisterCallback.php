<?php
namespace DevpeakIT\PWDSafe\Callbacks;

class PreLogonRegisterCallback
{
        /**
         * @brief Used for creating new user by email and password
         */
        public function post()
        {
                die(); // Easy way to disable function for now
                if (isset($_POST['user']) && isset($_POST['pass'])) {
                        $p = password_hash($_POST['pass'], PASSWORD_BCRYPT);
                        $sql = "INSERT INTO users(email, password) VALUES (:email, :password)";
                        $stmt = DB::getInstance()->prepare($sql);
                        $stmt->execute(['email' => $_POST['user'], 'password' => $p]);
                        echo json_encode(['status' => 'OK']);
                        die();
                }
                die();
        }
}
