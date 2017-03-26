<?php
namespace DevpeakIT\PWDSafe\Callbacks;

use DevpeakIT\PWDSafe\RequireAuthorization;
use DevpeakIT\PWDSafe\Traits\ContainerInject;

class CredAddCallback extends RequireAuthorization
{
        use ContainerInject;
        /**
         * @brief Callback for adding credentials to the database (used via json)
         */
        public function post()
        {
                if (!isset($_POST['credu'])) {
                        return;
                }

                // Save new credentials
                $reqfields = ['creds', 'credu', 'credp', 'currentgroupid'];
                if ($this->container->getFormchecker()->checkRequiredFields($reqfields)) {
                        $credentials = $this->container->getCredentials();

                        $credentials->add(
                            $_POST['creds'],
                            $_POST['credu'],
                            $_POST['credp'],
                            "",
                            $_POST['currentgroupid']
                        );
                        echo json_encode(['status' => 'OK']);
                }
        }
}
