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
                if (FormChecker::checkRequiredFields(['user', 'pass'])) {
                        $user = preg_replace('/[^A-Za-z0-9-_@\.]/', '', $_POST['user']);

                        if ($_POST['user'] !== $user) {
                                echo json_encode([
                                    'status' => 'Fail',
                                    'reason' => 'Your username contains invalid characters'
                                ]);
                                return;
                        }

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
}
