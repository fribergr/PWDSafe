<?php
namespace DevpeakIT\PWDSafe\Callbacks;

use DevpeakIT\PWDSafe\DB;
use DevpeakIT\PWDSafe\FormChecker;
use DevpeakIT\PWDSafe\RequireAuthorization;
use DevpeakIT\PWDSafe\GUI\Graphics;
use DevpeakIT\PWDSafe\Traits\ContainerInject;
use DevpeakIT\PWDSafe\Traits\ClickableLinks;

class SearchCallback extends RequireAuthorization
{
        use ContainerInject;
        use ClickableLinks;
        public function get($search)
        {
                $sql = "SELECT CASE WHEN groups.id = users.primarygroup THEN 'Private' ELSE groups.name END AS groupname,
                        groups.id AS groupid, credentials.id, credentials.site, credentials.username FROM credentials
                        INNER JOIN groups ON credentials.groupid = groups.id
                        INNER JOIN usergroups ON groups.id = usergroups.groupid
                        INNER JOIN users ON usergroups.userid = users.id
                        WHERE users.id = :userid AND (credentials.site LIKE :sitelike OR credentials.username LIKE :usernamelike)";
                $stmt = $this->container->getDb()->prepare($sql);
                $stmt->execute([
                        'userid' => $_SESSION['id'],
                        'sitelike' => "%$search%",
                        'usernamelike' => "%$search%",
                ]);
                $originaldata = $stmt->fetchAll();
                $data = [];
                foreach ($originaldata as $row) {
                        $data[] = [
                                'groupname' => $row['groupname'],
                                'groupid' => $row['groupid'],
                                'id' => $row['id'],
                                'site' => $this->makeLinksClickable($row['site']),
                                'username' => $row['username']
                        ];
                }

                $graphics = new Graphics();
                $graphics->showSearch($data);
        }
}
