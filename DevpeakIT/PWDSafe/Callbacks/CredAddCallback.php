<?php
namespace DevpeakIT\PWDSafe\Callbacks;

use DevpeakIT\PWDSafe\Encryption;
use DevpeakIT\PWDSafe\Credentials;
use DevpeakIT\PWDSafe\DB;

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
                $this->checkRequiredFields($reqfields);

                $encryption = new Encryption();
                $credentials = new Credentials();
                $credentials->setDB(DB::getInstance());
                
                $pwd = $encryption->enc($_POST['credp'], $_SESSION['pass']);
                $credentials->add($_POST['creds'], $_POST['credu'], $pwd, "", $_SESSION['id']);
                echo json_encode(['status' => 'OK']);
                die();
        }

        /**
         * @param $reqfields array with POST-fields we require for saving credentials
         */
        private function checkRequiredFields($reqfields)
        {
                foreach ($reqfields as $fld) {
                        if (!isset($_POST[$fld])) {
                                echo json_encode([
                                    'status' => 'Fail',
                                    'reason' => "Field '" . $fld . "' required but not set."
                                ]);
                                die();
                        }
                }
        }
}
