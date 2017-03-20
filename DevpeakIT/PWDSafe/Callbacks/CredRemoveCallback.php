<?php
namespace DevpeakIT\PWDSafe\Callbacks;

use DevpeakIT\PWDSafe\Exceptions\AuthorizationFailedException;
use DevpeakIT\PWDSafe\Credentials;
use DevpeakIT\PWDSafe\Encryption;
use DevpeakIT\PWDSafe\DB;

class CredRemoveCallback
{
        /**
         * @brief Callback for removing credentials from the database (used via json)
         * @param $id int id of credential to remove
         */
        public function get($id)
        {
                $credentials = new Credentials();
                $credentials->setDb(DB::getInstance());

                try {
                        $credentials->removeCred($_SESSION['id'], $id);
                } catch (AuthorizationFailedException $ex) {
                        echo json_encode([
                                'status' => 'Fail',
                                'reason' => 'Authorisation failed, you do not have access to the requested credentials'
                        ]);
                        return;
                }
                echo json_encode(['status' => 'OK']);
        }
}
