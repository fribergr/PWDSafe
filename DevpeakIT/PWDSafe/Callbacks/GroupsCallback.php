<?php
namespace DevpeakIT\PWDSafe\Callbacks;

use DevpeakIT\PWDSafe\DB;
use DevpeakIT\PWDSafe\RequireAuthorization;

class GroupsCallback extends RequireAuthorization
{
        public function get()
        {
                $db = DB::getInstance();
                $sql = "SELECT groups.id,
                        CASE WHEN groups.id = users.primarygroup THEN 'Private' ELSE groups.name END AS `name`,
                        COUNT(credentials.id) AS cnt FROM groups
                        LEFT JOIN credentials ON credentials.groupid = groups.id
                        INNER JOIN usergroups ON groups.id = usergroups.groupid
                        INNER JOIN users ON usergroups.userid = users.id
                        WHERE users.id = :userid GROUP BY groups.id
                        ORDER BY CASE WHEN groups.id = users.primarygroup THEN 1 ELSE 2 END, LOWER(`name`) ASC";
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
