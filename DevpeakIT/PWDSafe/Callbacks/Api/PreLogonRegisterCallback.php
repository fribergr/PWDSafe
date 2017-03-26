<?php
namespace DevpeakIT\PWDSafe\Callbacks\Api;

use DevpeakIT\PWDSafe\Container;

class PreLogonRegisterCallback
{
        private $container;
        public function __construct(Container $container)
        {
                $this->container = $container;
        }

        /**
         * @brief Used for creating new user by email and password
         */
        public function post()
        {
                $fc = $this->container->getFormchecker();
                if ($fc->checkRequiredFields(['user', 'pass'])) {
                        $user = preg_replace('/[^A-Za-z0-9-_@\.]/', '', $_POST['user']);

                        if ($_POST['user'] !== $user) {
                                echo json_encode([
                                    'status' => 'Fail',
                                    'reason' => 'Your username contains invalid characters'
                                ]);
                                return;
                        }

                        $sql = "SELECT id FROM users WHERE email = :email";
                        $stmt = $this->container->getDB()->prepare($sql);
                        $stmt->execute(['email' => $_POST['user']]);

                        if ($stmt->rowCount() > 0) {
                                echo json_encode([
                                    'status' => 'Fail',
                                    'reason' => 'Account already exists'
                                ]);
                                return;
                        }

                        $this->container->getUser()->registerUser(
                            $this->container->getEncryption(),
                            $_POST['user'],
                            $_POST['pass']
                        );
                        echo json_encode(['status' => 'OK']);
                }
        }
}
