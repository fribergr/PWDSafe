<?php
namespace DevpeakIT\PWDSafe\Callbacks;

use DevpeakIT\PWDSafe\DB;
use DevpeakIT\PWDSafe\Encryption;
use DevpeakIT\PWDSafe\FormChecker;
use DevpeakIT\PWDSafe\GUI\Graphics;

class GroupsShareCallback
{
        public function get($groupid = null)
        {
                if (is_null($groupid)) {
                        $groupid = $_SESSION['primarygroup'];
                }

                $access_sql = "SELECT groups.id, groups.name FROM groups
                               INNER JOIN usergroups ON usergroups.groupid = groups.id
                               INNER JOIN users ON users.id = usergroups.userid
                               WHERE users.id = :userid AND groups.id = :groupid";
                $access_stmt = DB::getInstance()->prepare($access_sql);
                $access_stmt->execute([
                    'userid' => $_SESSION['id'],
                    'groupid' => $groupid
                ]);

                if ($access_stmt->rowCount() === 0) {
                        $graphics = new Graphics();
                        $graphics->showUnathorized();
                        die();
                }
                $res = $access_stmt->fetch();
                $groupname = $res['name'];

                $sql = "SELECT users.id, users.email FROM users
                        INNER JOIN usergroups ON usergroups.userid = users.id
                        WHERE usergroups.groupid = :group AND users.id != :userid";
                $stmt = DB::getInstance()->prepare($sql);
                $stmt->execute([
                    'group' => $groupid,
                    'userid' => $_SESSION['id']
                ]);

                $data = $stmt->fetchAll();
                $graphics = new Graphics();
                $graphics->showShareGroup($data, $groupid, $groupname);
        }

        public function post($groupid = null)
        {
                FormChecker::checkRequiredFields(['email']);
                $access_sql = "SELECT groups.id, groups.name FROM groups
                               INNER JOIN usergroups ON usergroups.groupid = groups.id
                               INNER JOIN users ON users.id = usergroups.userid
                               WHERE users.id = :userid AND groups.id = :groupid";
                $access_stmt = DB::getInstance()->prepare($access_sql);
                $access_stmt->execute([
                    'userid' => $_SESSION['id'],
                    'groupid' => $groupid
                ]);

                if ($access_stmt->rowCount() === 0) {
                        echo json_encode(['status' => 'Fail', 'resaon' => 'Unauthorized']);
                        die();
                }

                // Make sure new user exists, grab pubkey
                $sql = "SELECT id, pubkey FROM users WHERE email = :email";
                $stmt = DB::getInstance()->prepare($sql);
                $stmt->execute(['email' => $_POST['email']]);
                if ($stmt->rowCount() === 0) {
                        echo json_encode(['status' => 'Fail', 'reason' => 'User does not exist']);
                        die();
                }
                $newuser = $stmt->fetch();

                // Make sure user is not already in group
                $sql = "SELECT users.id FROM users INNER JOIN usergroups ON usergroups.userid = users.id
                        WHERE usergroups.groupid = :groupid AND users.email = :email";
                $stmt = DB::getInstance()->prepare($sql);
                $stmt->execute(['email' => $_POST['email'], 'groupid' => $groupid]);
                if ($stmt->rowCount() > 0) {
                        echo json_encode(['status' => 'Fail', 'reason' => 'User already in group']);
                        die();
                }

                // Grab all credentials for group, decode and reinsert with the new users pubkey
                $sql = "SELECT encryptedcredentials.data, encryptedcredentials.credentialid FROM encryptedcredentials
                        INNER JOIN credentials ON credentials.id = encryptedcredentials.credentialid
                        INNER JOIN groups ON credentials.groupid = groups.id
                        WHERE groups.id = :groupid";
                $stmt = DB::getInstance()->prepare($sql);
                $stmt->execute(['groupid' => $groupid]);

                $insert_sql = "INSERT INTO encryptedcredentials (credentialid, userid, data)
                               VALUES (:credid, :userid, :data)";
                $insert_stmt = DB::getInstance()->prepare($insert_sql);

                $enc = new Encryption();
                while ($row = $stmt->fetch()) {
                        $data = $enc->decWithPriv(
                            base64_decode($row['data']),
                            $enc->dec($_SESSION['privkey'], $_SESSION['pass'])
                        );
                        
                        $insert_stmt->execute([
                            'credid' => $row['credentialid'],
                            'userid' => $newuser['id'],
                            'data' => base64_encode($enc->encWithPub($data, $newuser['pubkey']))
                        ]);
                }

                // Add new user to usergroups for particular group
                $sql = "INSERT INTO usergroups (userid, groupid) VALUES (:userid, :groupid)";
                $stmt = DB::getInstance()->prepare($sql);
                $stmt->execute(['userid' => $newuser['id'], 'groupid' => $groupid]);

                echo json_encode(['status' => 'OK']);
        }
}
