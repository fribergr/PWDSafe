<?php
namespace DevpeakIT\PWDSafe\Callbacks;

use DevpeakIT\PWDSafe\DB;

class GroupsChangeNameCallback
{
        public function post($groupid)
        {
                if (
                    !isset($_POST['groupname'])
                    || strlen(trim($_POST['groupname'])) === 0
                    || is_null($groupid)
                    || !is_numeric($groupid)
                ) {
                        echo json_encode([
                            'status' => 'Fail',
                            'reason' => 'Missing groupid'
                        ]);
                        return;
                }

                $groupname = preg_replace('/[^\p{L}\p{N}-_ ]/u', "", trim($_POST['groupname']));
                $groupname = substr($groupname,0, 100);

                // Check access
                $access_sql = "SELECT groups.id FROM groups
                               INNER JOIN usergroups ON groups.id = usergroups.groupid
                               INNER JOIN users ON usergroups.userid = users.id
                               WHERE usergroups.userid = :userid AND usergroups.groupid = :groupid";
                $access_stmt = DB::getInstance()->prepare($access_sql);
                $access_stmt->execute([
                    'userid' => $_SESSION['id'],
                    'groupid' => $groupid
                ]);

                if ($access_stmt->rowCount() === 0) {
                        echo json_encode([
                            'status' => 'Fail',
                            'reason' => 'Unauthorized'
                        ]);
                        return;
                }
                $sql = "UPDATE groups SET name = :groupname WHERE id = :groupid";
                $stmt = DB::getInstance()->prepare($sql);
                $stmt->execute(['groupname' => $groupname, 'groupid' => $groupid]);
                echo json_encode(['status' => 'OK']);
        }
}