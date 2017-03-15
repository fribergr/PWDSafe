<?php
namespace DevpeakIT\PWDSafe;

use DevpeakIT\PWDSafe\Authentication\LDAPAuthentication;

class Session
{
        /**
         * @brief wrapper for session_start
         */
        public function start()
        {
                session_start();
        }

        /**
         * @return bool
         */
        public function isLoggedIn()
        {
                return isset($_SESSION['id']);
        }

        /**
         * @brief wrapper for session_destroy
         */
        public function logout()
        {
                session_destroy();
        }

        /**
         * @param \PDO $db
         * @param $user string email adress
         * @param $pass string password (plaintext)
         * @return bool|mixed
         */
        public function authenticate(\PDO $db, $user, $pass)
        {
            if (USE_LDAP) {
                $res = LDAPAuthentication::login($user, $pass);
                if ($res) {
                    $sql = "SELECT id, email, pubkey, privkey, primarygroup FROM users WHERE email = :email";
                    $stmt = $db->prepare($sql);
                    $stmt->execute(['email' => $user]);
                    if ($stmt->rowCount() === 0) {
                        // We should register a new account
                        return User::registerUser(new Encryption(), $user, $pass);
                    } else {
                        // We already have an account. Grab information and return
                        return User::getData($user, $pass, true);
                    }
                } else {
                    return false;
                }
            } else {
                $row = User::getData($user, $pass);

                if (password_verify($pass, $row['encryptedpassword'])) {
                        $sql = "UPDATE users SET lastlogin = NOW() WHERE email = :email";
                        $stmt = $db->prepare($sql);
                        $stmt->execute(['email' => $user]);
                        return $row;
                } else {
                        return false;
                }
            }
        }
}
