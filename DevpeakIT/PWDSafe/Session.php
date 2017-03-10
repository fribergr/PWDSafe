<?php
namespace DevpeakIT\PWDSafe;

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
                $sql = "SELECT id, email, password, pubkey, privkey FROM users WHERE email = :email";
                $stmt = $db->prepare($sql);
                $stmt->execute(['email' => $user]);

                if ($stmt->rowCount() > 0) {
                        $row = $stmt->fetch();
                        if (password_verify($pass, $row['password'])) {
                                $sql = "UPDATE users SET lastlogin = NOW() WHERE email = :email";
                                $stmt = $db->prepare($sql);
                                $stmt->execute(['email' => $user]);
                                $row['password'] = $pass;
                                return $row;
                        } else {
                                return false;
                        }
                } else {
                        return false;
                }
        }
}
