<?php
namespace DevpeakIT\PWDSafe\Callbacks;

use DevpeakIT\PWDSafe\DB;
use DevpeakIT\PWDSafe\Group;
use DevpeakIT\PWDSafe\RequireAuthorization;
use DevpeakIT\PWDSafe\GUI\Graphics;
use DevpeakIT\PWDSafe\Traits\ClickableLinks;

class GroupsSpecificCallback extends RequireAuthorization
{
        use ClickableLinks;
        public function get($num = null)
        {
                if (is_null($num)) {
                        $num = $_SESSION['primarygroup'];
                }

                $access_sql = "SELECT groups.id, groups.name FROM groups
                               INNER JOIN usergroups ON usergroups.groupid = groups.id
                               INNER JOIN users ON users.id = usergroups.userid
                               WHERE users.id = :userid AND groups.id = :groupid";
                $access_stmt = DB::getInstance()->prepare($access_sql);
                $access_stmt->execute([
                    'userid' => $_SESSION['id'],
                    'groupid' => $num
                ]);

                if ($access_stmt->rowCount() === 0) {
                        $graphics = new Graphics();
                        $graphics->showUnathorized();
                        return;
                }
                $res = $access_stmt->fetch();
                $currentgroup = $res;

                $sql = "SELECT groups.id as groupid, credentials.id, credentials.site, credentials.username, credentials.notes FROM credentials
                        INNER JOIN groups ON credentials.groupid = groups.id
                        INNER JOIN usergroups ON groups.id = usergroups.groupid
                        INNER JOIN users ON usergroups.userid = users.id
                        WHERE users.id = :userid AND groups.id = :group";
                $stmt = DB::getInstance()->prepare($sql);
                $stmt->execute([
                    'userid' => $_SESSION['id'],
                    'group' => $num
                ]);

                $originaldata = $stmt->fetchAll();
                $data = [];
                foreach ($originaldata as $row) {
                        $data[] = [
                                'groupid' => $row['groupid'],
                                'id' => $row['id'],
                                'notes' => nl2br($this->makeLinksClickable($row['notes']), false),
                                'site' => $this->makeLinksClickable($row['site']),
                                'username' => $row['username']
                        ];
                }

                $groups = Group::getAllGroupsForUser($_SESSION['id']);
                $graphics = new Graphics();
                $graphics->showGroup($data, $num, $currentgroup, $groups);
        }
}
