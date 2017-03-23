<?php
namespace DevpeakIT\PWDSafe\Callbacks;

use DevpeakIT\PWDSafe\DB;
use DevpeakIT\PWDSafe\RequireAuthorization;

class GroupsCallback extends RequireAuthorization
{
        public function get()
        {
                $db = DB::getInstance();
                $sql = "SELECT groups.id, groups.name FROM groups
                        INNER JOIN usergroups ON groups.id = usergroups.groupid
                        INNER JOIN users ON usergroups.userid = users.id
                        WHERE users.id = :userid AND groups.id != users.primarygroup
                        ORDER BY groups.name ASC";
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    'userid' => $_SESSION['id']
                ]);
                $data = $stmt->fetchAll();

                echo json_encode([
                    'status' => 'OK',
                    'groups' => $data
                ]);
        }
}