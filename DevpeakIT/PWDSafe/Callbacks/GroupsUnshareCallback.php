<?php
namespace DevpeakIT\PWDSafe\Callbacks;

use DevpeakIT\PWDSafe\DB;
use DevpeakIT\PWDSafe\Group;

class GroupsUnshareCallback
{
        public function post($groupid = null, $userid = null)
        {
                if (is_null($groupid) || is_null($userid) || !is_numeric($groupid) || !is_numeric($userid)) {
                        echo json_encode([
                            'status' => 'Fail',
                            'reason' => 'Missing groupid or userid'
                        ]);
                        die();
                }

                // Check access
                $grp = new Group();
                $grp->id = $groupid;

                if (!$grp->checkAccess($_SESSION['id'])) {
                        echo json_encode([
                            'status' => 'Fail',
                            'reason' => 'Unauthorized'
                        ]);
                        die();
                }

                // Access OK, remove credentials for this user in this group
                $sql = "DELETE FROM encryptedcredentials
                        WHERE userid = :userid AND credentialid IN (
                          SELECT id FROM credentials WHERE groupid = :groupid
                        )";
                $stmt = DB::getInstance()->prepare($sql);
                $stmt->execute([
                    'userid' => $userid,
                    'groupid' => $groupid
                ]);

                // Remove user from group
                $sql = "DELETE FROM usergroups WHERE groupid = :groupid AND userid = :userid LIMIT 1";
                $stmt = DB::getInstance()->prepare($sql);
                $stmt->execute([
                    'groupid' => $groupid,
                    'userid' => $userid
                ]);

                echo json_encode([
                    'status' => 'OK'
                ]);
        }
}