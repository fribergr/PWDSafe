<?php
namespace DevpeakIT\PWDSafe;

use DevpeakIT\PWDSafe\Exceptions\AuthorizationFailedException;
use PDO;

class Credentials
{
        /**
         * @var PDO
         */
        private $db;

        public function setDb(PDO $db)
        {
                $this->db = $db;
        }

        /**
         * @brief Get credentials from the database
         * @param $userid int
         * @param $pwdid int
         * @return array
         * @throws AuthorizationFailedException
         */
        public function getPwdFor($userid, $pwdid)
        {
                $sql = "SELECT username, site, password FROM credentials WHERE id = :id AND userid = :userid";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                        'id' => $pwdid,
                        'userid' => $userid
                ]);

                if ($stmt->rowCount() == 0) {
                        throw new AuthorizationFailedException();
                } else {
                        $res = $stmt->fetch();
                        return [
                                'user' => $res['username'],
                                'site' => $res['site'],
                                'pass' => $res['password']
                        ];
                }
        }

        /**
         * @brief Remove credentials from database
         * @param $userid int
         * @param $credid int
         * @throws AuthorizationFailedException
         */
        public function removeCred($userid, $credid)
        {
                $sql = "DELETE FROM credentials WHERE id = :id AND userid = :userid LIMIT 1";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                        'id' => $credid,
                        'userid' => $userid
                ]);

                if ($stmt->rowCount() == 0) {
                        throw new AuthorizationFailedException();
                }
        }

        /**
         * @brief Add credentials in database
         * @param $site string
         * @param $username string
         * @param $password string
         * @param $notes string
         * @param $userid int
         */
        public function add($site, $username, $password, $notes, $userid)
        {
                $sql = "INSERT INTO credentials(userid, site, username, password, notes)
                        VALUES(:userid, :site, :username, :password, :notes)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                        'userid' => $userid,
                        'site' => $site,
                        'username' => $username,
                        'password' => $password,
                        'notes' => $notes
                ]);
        }
}
