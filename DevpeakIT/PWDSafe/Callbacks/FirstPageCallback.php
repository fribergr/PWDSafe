<?php
namespace DevpeakIT\PWDSafe\Callbacks;

use DevpeakIT\PWDSafe\DB;
use DevpeakIT\PWDSafe\GUI\Graphics;

class FirstPageCallback
{
        /**
         * @brief Show logged in page
         */
        public function get()
        {
                $db = DB::getInstance();
                $sql = "SELECT credentials.id, credentials.site, credentials.username FROM credentials
                        INNER JOIN groups ON credentials.groupid = groups.id
                        INNER JOIN usergroups ON groups.id = usergroups.groupid
                        INNER JOIN users ON usergroups.userid = users.id
                        WHERE users.id = :userid AND groups.id = :group";
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    'userid' => $_SESSION['id'],
                    'group' => $_SESSION['primarygroup']
                ]);
                $data = $stmt->fetchAll();
                $graphics = new Graphics();
                $graphics->showGroup($data, $_SESSION['primarygroup'], "");
        }
}
