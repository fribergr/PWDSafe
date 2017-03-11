<?php
namespace DevpeakIT\PWDSafe\Callbacks;

use DevpeakIT\PWDSafe\DB;
use DevpeakIT\PWDSafe\Encryption;

class PreLogonRegisterCallback
{
        /**
         * @brief Used for creating new user by email and password
         */
        public function post()
        {
                if (isset($_POST['user']) && isset($_POST['pass'])) {

                    list($privKey, $pubKey) = Encryption::genNewKeys();
                    $enc = new Encryption();
                    $privKey = $enc->enc($privKey, $_POST['pass']);


                        $p = password_hash($_POST['pass'], PASSWORD_BCRYPT);

                        $sql = "INSERT INTO groups (name) VALUE (:email)";
                        $stmt = DB::getInstance()->prepare($sql);
                        $stmt->execute(['email' => $_POST['user']]);
                        $groupid = DB::getInstance()->lastInsertId();

                        $sql = "INSERT INTO users(email, password, pubkey, privkey, primarygroup)
                                VALUES (:email, :password, :pubkey, :privkey, :primarygroup)";
                        $stmt = DB::getInstance()->prepare($sql);
                        $stmt->execute([
                            'email' => $_POST['user'],
                            'password' => $p,
                            'pubkey' => $pubKey,
                            'privkey' => $privKey,
                            'primarygroup' => $groupid
                        ]);
                        $userid = DB::getInstance()->lastInsertId();

                        $sql = "INSERT INTO usergroups (userid, groupid) VALUES (:userid, :groupid)";
                        $stmt = DB::getInstance()->prepare($sql);
                        $stmt->execute(['userid' => $userid, 'groupid' => $groupid]);
                        echo json_encode(['status' => 'OK']);
                } else {
                    echo json_encode(['status' => 'ERROR']);
                }
                die();
        }
}
