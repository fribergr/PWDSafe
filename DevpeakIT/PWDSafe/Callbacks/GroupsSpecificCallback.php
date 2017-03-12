<?php
namespace DevpeakIT\PWDSafe\Callbacks;

use DevpeakIT\PWDSafe\DB;
use DevpeakIT\PWDSafe\GUI\Graphics;

class GroupsSpecificCallback
{
        public function get($num = null) {
                if (is_null($num)) {
                        $num = $_SESSION['primarygroup'];
                }

                $db = DB::getInstance();
                $sql = "SELECT credentials.id, credentials.site, credentials.username FROM credentials
                        INNER JOIN groups ON credentials.groupid = groups.id
                        INNER JOIN usergroups ON groups.id = usergroups.groupid
                        INNER JOIN users ON usergroups.userid = users.id
                        WHERE users.id = :userid AND groups.id = :group";
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    'userid' => $_SESSION['id'],
                    'group' => $num
                ]);
                $data = $stmt->fetchAll();
                $graphics = new Graphics();
                $graphics->showGroup($data, $num);
        }
}