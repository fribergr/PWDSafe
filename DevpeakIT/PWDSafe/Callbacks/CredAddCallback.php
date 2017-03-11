<?php
namespace DevpeakIT\PWDSafe\Callbacks;

use DevpeakIT\PWDSafe\Encryption;
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

                $encryption = new Encryption();
                $credentials = new Credentials();
                $credentials->setDB(DB::getInstance());

                $pwd = base64_encode($encryption->encWithPub($_POST['credp'], $_SESSION['pubkey']));
                $credentials->add($_POST['creds'], $_POST['credu'], $pwd, "", $_SESSION['id']);
                echo json_encode(['status' => 'OK']);
                die();
        }
}
