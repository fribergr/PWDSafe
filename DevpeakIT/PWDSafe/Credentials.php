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

        public function __construct(PDO $db)
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
                $sql = "SELECT username, site, encryptedcredentials.data FROM credentials
                        INNER JOIN groups ON groups.id = credentials.groupid
                        INNER JOIN usergroups ON groups.id = usergroups.groupid
                        INNER JOIN users ON users.id = usergroups.userid
                        INNER JOIN encryptedcredentials ON credentials.id = encryptedcredentials.credentialid
                        WHERE credentials.id = :id AND encryptedcredentials.userid = :userid";
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
                                'pass' => $res['data']
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
                $sql = "DELETE FROM encryptedcredentials WHERE credentialid = :id AND userid = :userid LIMIT 1";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                        'id' => $credid,
                        'userid' => $userid
                ]);

                if ($stmt->rowCount() == 0) {
                        throw new AuthorizationFailedException();
                } else {
                        $sql = "DELETE FROM encryptedcredentials WHERE credentialid = :id";
                        $stmt = $this->db->prepare($sql);
                        $stmt->execute(['id' => $credid]);
                        $sql = "DELETE FROM credentials WHERE id = :id";
                        $stmt = $this->db->prepare($sql);
                        $stmt->execute(['id' => $credid]);
                }
        }

        public function update($userid, $credid, $site, $user, $pass)
        {
                $sql = "SELECT credentialid FROM encryptedcredentials
                        WHERE credentialid = :id AND userid = :userid LIMIT 1";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                        'id' => $credid,
                        'userid' => $userid
                ]);

                $enc = new Encryption();

                if ($stmt->rowCount() == 0) {
                        throw new AuthorizationFailedException();
                } else {
                        $sql = "UPDATE credentials SET site = :site, username = :user
                                WHERE id = :id LIMIT 1";
                        $stmt = $this->db->prepare($sql);
                        $stmt->execute([
                                'id' => $credid,
                                'site' => $site,
                                'user' => $user
                        ]);

                        $sql = "SELECT users.id, users.pubkey FROM users
                                INNER JOIN encryptedcredentials ON users.id = encryptedcredentials.userid
                                WHERE encryptedcredentials.credentialid = :credentialid";
                        $stmt = $this->db->prepare($sql);
                        $stmt->execute(['credentialid' => $credid]);

                        $sql_update = "UPDATE encryptedcredentials SET data = :data
                                       WHERE credentialid = :credentialid AND userid = :userid";
                        $stmt_update = $this->db->prepare($sql_update);
                        while ($row = $stmt->fetch()) {
                                $stmt_update->execute([
                                        'credentialid' => $credid,
                                        'userid' => $row['id'],
                                        'data' => base64_encode($enc->encWithPub($pass, $row['pubkey']))
                                ]);
                        }
                }
        }

        /**
         * @brief Add credentials in database
         * @param $site string
         * @param $username string
         * @param $password string
         * @param $notes string
         * @param $groupid null|integer
         */
        public function add($site, $username, $password, $notes, $groupid = null)
        {
                if (is_null($groupid)) {
                        $groupid = $_SESSION['primarygroup'];
                }

                $enc = new Encryption();

                $sql = "INSERT INTO credentials(groupid, site, username, notes)
                        VALUES(:groupid, :site, :username, :notes)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    'groupid' => $groupid,
                    'site' => $site,
                    'username' => $username,
                    'notes' => $notes
                ]);
                $credentialid = $this->db->lastInsertId();

                $sql = "SELECT userid, pubkey FROM usergroups INNER JOIN users ON users.id = usergroups.userid
                        WHERE usergroups.groupid = :groupid";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(['groupid' => $groupid]);

                $sql_insert = "INSERT INTO encryptedcredentials(credentialid, userid, data)
                               VALUES (:credentialid, :userid, :data)";
                $stmt_insert = $this->db->prepare($sql_insert);
                while ($row = $stmt->fetch()) {
                        $stmt_insert->execute([
                            'credentialid' => $credentialid,
                            'userid' => $row['userid'],
                            'data' => base64_encode($enc->encWithPub($password, $row['pubkey']))
                        ]);
                }
        }
}
