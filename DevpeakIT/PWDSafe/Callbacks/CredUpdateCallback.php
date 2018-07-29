<?php
namespace DevpeakIT\PWDSafe\Callbacks;

use DevpeakIT\PWDSafe\RequireAuthorization;
use DevpeakIT\PWDSafe\Traits\ContainerInject;

class CredUpdateCallback extends RequireAuthorization
{
        use ContainerInject;
        /**
         * @brief Callback for adding credentials to the database (used via json)
         */
        public function post($id)
        {
                if (!$id) {
                        return;
                }

                // Update credentials
                $reqfields = ['site', 'user', 'pass'];
                if ($this->container->getFormchecker()->checkRequiredFields($reqfields)) {
                        $credentials = $this->container->getCredentials();

                        $credentials->update(
                            $_SESSION['id'],
                            $id,
                            $_POST['site'],
                            $_POST['user'],
                            $_POST['pass'],
                            isset($_POST['notes']) ? $_POST['notes'] : ""
                        );
                        echo json_encode(['status' => 'OK']);
                }
        }
}
