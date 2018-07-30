<?php
namespace DevpeakIT\PWDSafe\Callbacks;

use DevpeakIT\PWDSafe\DB;
use DevpeakIT\PWDSafe\RequireAuthorization;
use DevpeakIT\PWDSafe\Encryption;
use DevpeakIT\PWDSafe\GUI\Graphics;
use DevpeakIT\PWDSafe\Traits\ContainerInject;
use DevpeakIT\PWDSafe\Traits\ClickableLinks;

class SecurityCheckCallback extends RequireAuthorization
{
        use ContainerInject;
        use ClickableLinks;

        public function get()
        {
                $sql = "SELECT CASE WHEN groups.id = users.primarygroup THEN 'Private' ELSE groups.name END AS groupname,
                        groups.id AS groupid, credentials.id, credentials.site, credentials.username, credentials.notes, encryptedcredentials.data AS pass FROM credentials
                        INNER JOIN groups ON credentials.groupid = groups.id
                        INNER JOIN usergroups ON groups.id = usergroups.groupid
                        INNER JOIN users ON usergroups.userid = users.id
                        INNER JOIN encryptedcredentials ON encryptedcredentials.credentialid = credentials.id
                        WHERE users.id = :userid AND encryptedcredentials.userid = users.id";
                $stmt = $this->container->getDb()->prepare($sql);
                $stmt->execute([
                        'userid' => $_SESSION['id'],
                ]);
                $originaldata = $stmt->fetchAll();
                $data = [];

                $encryption = new Encryption();

                foreach ($originaldata as $row) {
                        $pwd = $encryption->decWithPriv(
                            base64_decode($row['pass']),
                            $encryption->dec($_SESSION['privkey'], $_SESSION['pass'])
                        );
                        $data[$pwd][] = [
                                'groupname' => $row['groupname'],
                                'groupid' => $row['groupid'],
                                'notes' => $row['notes'],
                                'id' => $row['id'],
                                'site' => $this->makeLinksClickable($row['site']),
                                'username' => $row['username']
                        ];
                }

                $hassame = [];
                foreach ($data as $pwd) {
                        if (count($pwd) > 1) {
                                $hassame[] = $pwd;
                        }
                }

                $graphics = new Graphics();
                $graphics->showSecurityCheck($hassame);
        }
}
