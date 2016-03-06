<?php
namespace DevpeakIT\PWDSafe\Callbacks;

use \DevpeakIT\PWDSafe\Exceptions\AuthorizationFailedException;
use \DevpeakIT\PWDSafe\Credentials;
use \DevpeakIT\PWDSafe\Encryption;
use \DevpeakIT\PWDSafe\DB;

class PasswordForcallback
{
        /**
         * @brief Used for getting credentials based on id
         * @param $id int credential id
         */
        public function get($id)
        {
                $pwd = $this->getCredForID($id);

                $encryption = new Encryption();

                $user = $pwd['user'];
                $site = $pwd['site'];
                $pwd = $pwd['pass'];
                $pwd = $encryption->dec($pwd, $_SESSION['pass']);
                echo json_encode([
                        'status' => 'OK',
                        'pwd' => $pwd,
                        'user' => $user,
                        'site' => $site
                ]);
                die();
        }

        /**
         * @param $id int credential id
         * @return array containing site, username and password
         */
        private function getCredForID($id)
        {
                $credentials = new Credentials();
                $credentials->setDb(DB::getInstance());

                try {
                        return $credentials->getPwdFor($_SESSION['id'], $id);
                } catch (AuthorizationFailedException $ex) {
                        echo json_encode([
                            'status' => 'Fail',
                            'reason' => 'Authorisation failed, you do not have access to the requested credentials'
                        ]);
                        die();
                }
        }
}
