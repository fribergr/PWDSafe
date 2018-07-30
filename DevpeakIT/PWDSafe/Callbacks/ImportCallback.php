<?php
namespace DevpeakIT\PWDSafe\Callbacks;

use DevpeakIT\PWDSafe\DB;
use DevpeakIT\PWDSafe\FormChecker;
use DevpeakIT\PWDSafe\RequireAuthorization;
use DevpeakIT\PWDSafe\GUI\Graphics;
use DevpeakIT\PWDSafe\Traits\ContainerInject;

class ImportCallback extends RequireAuthorization
{
        use ContainerInject;
        public function get()
        {
                header("Location: /");
                return;
        }

        public function post()
        {
                if (!isset($_FILES['csvfile'])) {
                        header("Location: /");
                        return;
                }

                if ($this->container->getFormchecker()->checkRequiredFields(['group'])) {
                        // Check if current user has access to this group before importing data
                        $sql = "SELECT * FROM usergroups WHERE userid = :userid AND groupid = :groupid";
                        $stmt = $this->container->getDb()->prepare($sql);

                        $stmt->execute([
                                'userid' => $_SESSION['id'],
                                'groupid' => $_POST['group']
                        ]);

                        if ($stmt->rowCount() === 0) {
                                header("Location: /");
                                return;
                        }

                        $credentials = $this->container->getCredentials();
                        if (($fh = fopen($_FILES['csvfile']['tmp_name'], 'r')) !== false) {
                                while (($data = fgetcsv($fh)) !== false) {
                                        list ($site, $username, $password, $note) = $data;
                                        if (strlen($site) === 0 || strlen($password) === 0) {
                                                # Seems malformed, skip this row
                                                continue;
                                        }
                                        $credentials->add(
                                                $site,
                                                $username,
                                                $password,
                                                $note,
                                                $_POST['group']
                                        );
                                }
                        }
                        header("Location: /groups/" . $_POST['group']);
                        return;
                }
        }
}
