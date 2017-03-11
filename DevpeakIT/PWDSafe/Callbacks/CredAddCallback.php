<?php
namespace DevpeakIT\PWDSafe\Callbacks;

use DevpeakIT\PWDSafe\Credentials;
use DevpeakIT\PWDSafe\DB;
use DevpeakIT\PWDSafe\FormChecker;

class CredAddCallback
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
                $reqfields = ['creds', 'credu', 'credp'];
                FormChecker::checkRequiredFields($reqfields);

                $groupid = isset($_POST['groupid']) ? $_POST['groupid'] : $_SESSION['primarygroup'];

                $credentials = new Credentials();
                $credentials->setDB(DB::getInstance());

                $credentials->add($_POST['creds'], $_POST['credu'], $_POST['credp'], "", $groupid);
                echo json_encode(['status' => 'OK']);
                die();
        }
}
