<?php
namespace DevpeakIT\PWDSafe\Callbacks;

use DevpeakIT\PWDSafe\DB;
use DevpeakIT\PWDSafe\Encryption;
use DevpeakIT\PWDSafe\FormChecker;
use DevpeakIT\PWDSafe\GUI\Graphics;
use DevpeakIT\PWDSafe\PasswordChecker;

class ChangePwdCallback
{
        public function get()
        {
                $graphics = new Graphics();
                $graphics->showChangePwd();
        }

        public function post()
        {
                FormChecker::checkRequiredFields(['oldpwd', 'newpwd1', 'newpwd2']);
                PasswordChecker::checkPwdStrength($_SESSION['pass'], $_POST['oldpwd'], $_POST['newpwd1'], $_POST['newpwd2']);

                // Generate new public and private key
                list($privKey, $pubKey) = Encryption::genNewKeys();

                // Loop through all credentials for this user and reencrypt them with the new private key
                $sql = "SELECT id, data FROM encryptedcredentials WHERE userid = :userid";
                $stmt = DB::getInstance()->prepare($sql);
                $stmt->execute(['userid' => $_SESSION['id']]);

                $sql_update = "UPDATE encryptedcredentials SET data = :data WHERE id = :id";
                $stmt_update = DB::getInstance()->prepare($sql_update);

                $enc = new Encryption();
                while ($row = $stmt->fetch()) {
                        $data = $enc->decWithPriv(
                            base64_decode($row['data']),
                            $enc->dec($_SESSION['privkey'], $_SESSION['pass'])
                        );
                        $newdata = base64_encode($enc->encWithPub($data, $pubKey));
                        $stmt_update->execute([
                            'data' => $newdata,
                            'id' => $row['id']
                        ]);
                }

                // Encrypt private key with new password
                $encryptedprivkey = $enc->enc($privKey, $_POST['newpwd1']);

                // Update users-table with the new password (hashed) and the private key (encrypted)
                $sql = "UPDATE users SET password = :password, pubkey = :pubkey, privkey = :privkey WHERE id = :userid";
                $stmt = DB::getInstance()->prepare($sql);
                $stmt->execute([
                    'password' => password_hash($_POST['newpwd1'], PASSWORD_BCRYPT),
                    'pubkey' => $pubKey,
                    'privkey' => $encryptedprivkey,
                    'userid' => $_SESSION['id']
                ]);
                echo json_encode([
                    "status" => "OK"
                ]);
                $_SESSION['pass'] = $_POST['newpwd1'];
                $_SESSION['privkey'] = $encryptedprivkey;
        }
}
