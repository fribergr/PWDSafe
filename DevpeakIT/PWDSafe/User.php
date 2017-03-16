<?php
namespace DevpeakIT\PWDSafe;

use DevpeakIT\PWDSafe\Exceptions\AppException;

class User
{
        public static function registerUser(Encryption $enc, $username, $password)
        {
                list($privKey, $pubKey) = Encryption::genNewKeys();
                $privKey = $enc->enc($privKey, $password);

                $p = password_hash($password, PASSWORD_BCRYPT);

                $sql = "INSERT INTO groups (name) VALUE (:email)";
                $stmt = DB::getInstance()->prepare($sql);
                $stmt->execute(['email' => $username]);
                $groupid = DB::getInstance()->lastInsertId();

                $sql = "INSERT INTO users(email, password, pubkey, privkey, primarygroup)
                        VALUES (:email, :password, :pubkey, :privkey, :primarygroup)";
                $stmt = DB::getInstance()->prepare($sql);
                $insert_data = [
                    'email' => $username,
                    'password' => $p,
                    'pubkey' => $pubKey,
                    'privkey' => $privKey,
                    'primarygroup' => $groupid
                ];
                $stmt->execute($insert_data);
                $userid = DB::getInstance()->lastInsertId();

                $sql = "INSERT INTO usergroups (userid, groupid) VALUES (:userid, :groupid)";
                $stmt = DB::getInstance()->prepare($sql);
                $stmt->execute(['userid' => $userid, 'groupid' => $groupid]);
                return $insert_data;
        }

        public static function getData($user, $pass, $ldap = false)
        {
                $sql = "SELECT id, email, password AS encryptedpassword, pubkey, privkey, primarygroup
                FROM users WHERE email = :email";
                $stmt = DB::getInstance()->prepare($sql);
                $stmt->execute(['email' => $user]);

                if ($stmt->rowCount() > 0) {
                        $res = $stmt->fetch();
                        $res['password'] = $pass;
                        $res['ldap'] = $ldap;
                        return $res;
                } else {
                        return false;
                }
        }

        public static function changePassword($user, $currentpass, $newpass)
        {
                $sql = "SELECT id, password, privkey FROM users WHERE email = :username";
                $stmt = DB::getInstance()->prepare($sql);
                $stmt->execute(['username' => $user]);
                if ($stmt->rowCount() === 0) {
                        echo json_encode(['status' => 'Fail', 'reason' => 'No such user exists']);
                        die();
                }

                $res = $stmt->fetch();
                if (!password_verify($currentpass, $res['password'])) {
                        echo json_encode(['status' => 'Fail', 'reason' => 'Password is incorrect']);
                        die();
                }

                // Generate new public and private key
                list($privKey, $pubKey) = Encryption::genNewKeys();

                // Loop through all credentials for this user and reencrypt them with the new private key
                $sql = "SELECT id, data FROM encryptedcredentials WHERE userid = :userid";
                $stmt = DB::getInstance()->prepare($sql);
                $stmt->execute(['userid' => $res['id']]);

                $sql_update = "UPDATE encryptedcredentials SET data = :data WHERE id = :id";
                $stmt_update = DB::getInstance()->prepare($sql_update);

                $enc = new Encryption();
                while ($row = $stmt->fetch()) {
                        $data = $enc->decWithPriv(
                            base64_decode($row['data']),
                            $enc->dec($res['privkey'], $currentpass)
                        );
                        $newdata = base64_encode($enc->encWithPub($data, $pubKey));
                        $stmt_update->execute([
                            'data' => $newdata,
                            'id' => $row['id']
                        ]);
                }

                // Encrypt private key with new password
                $encryptedprivkey = $enc->enc($privKey, $newpass);

                // Update users-table with the new password (hashed) and the private key (encrypted)
                $sql = "UPDATE users SET password = :password, pubkey = :pubkey, privkey = :privkey WHERE id = :userid";
                $stmt = DB::getInstance()->prepare($sql);
                $stmt->execute([
                    'password' => password_hash($newpass, PASSWORD_BCRYPT),
                    'pubkey' => $pubKey,
                    'privkey' => $encryptedprivkey,
                    'userid' => $res['id']
                ]);
                echo json_encode([
                    "status" => "OK"
                ]);
                if (isset($_SESSION['pass'])) {
                        $_SESSION['pass'] = $newpass;
                        $_SESSION['privkey'] = $encryptedprivkey;
                }
        }
}