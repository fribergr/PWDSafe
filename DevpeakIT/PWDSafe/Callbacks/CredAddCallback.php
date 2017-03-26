<?php
namespace DevpeakIT\PWDSafe\Callbacks;

use DevpeakIT\PWDSafe\Credentials;
use DevpeakIT\PWDSafe\DB;
use DevpeakIT\PWDSafe\FormChecker;
use DevpeakIT\PWDSafe\RequireAuthorization;

class CredAddCallback extends RequireAuthorization
{
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
                $fc = new FormChecker();
                if ($fc->checkRequiredFields($reqfields)) {
                        $credentials = new Credentials();
                        $credentials->setDB(DB::getInstance());

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
