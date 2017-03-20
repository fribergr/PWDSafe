<?php
namespace DevpeakIT\PWDSafe\Callbacks;

use DevpeakIT\PWDSafe\DB;
use DevpeakIT\PWDSafe\Encryption;
use DevpeakIT\PWDSafe\FormChecker;
use DevpeakIT\PWDSafe\User;

class PreLogonRegisterCallback
{
        /**
         * @brief Used for creating new user by email and password
         */
        public function post()
        {
                FormChecker::checkRequiredFields(['user', 'pass']);

                $sql = "SELECT id FROM users WHERE email = :email";
                $stmt = DB::getInstance()->prepare($sql);
                $stmt->execute(['email' => $_POST['user']]);

                if ($stmt->rowCount() > 0) {
                        echo json_encode([
                            'status' => 'Fail',
                            'reason' => 'Account already exists'
                        ]);
                        return;
                }

                $enc = new Encryption();
                User::registerUser($enc, $_POST['user'], $_POST['pass']);
                echo json_encode(['status' => 'OK']);
        }
}
