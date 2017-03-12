<?php
namespace DevpeakIT\PWDSafe\Callbacks;

use DevpeakIT\PWDSafe\DB;
use DevpeakIT\PWDSafe\FormChecker;
use DevpeakIT\PWDSafe\GUI\Graphics;

class GroupCreateCallback
{
        public function get()
        {
                $graphics = new Graphics();
                $graphics->showCreateGroup();
        }

        public function post()
        {
                FormChecker::checkRequiredFields(['groupname']);
                FormChecker::checkFieldLength('groupname', 1);
                $sql = "INSERT INTO groups (name) VALUES(:name)";
                $stmt = DB::getInstance()->prepare($sql);
                $stmt->execute(['name' => $_POST['groupname']]);
                $groupid = DB::getInstance()->lastInsertId();
                $sql = "INSERT INTO usergroups (userid, groupid) VALUES (:userid, :groupid)";
                $stmt = DB::getInstance()->prepare($sql);
                $stmt->execute([
                    'userid' => $_SESSION['id'],
                    'groupid' => $groupid
                ]);
                echo json_encode(['status' => "OK"]);
        }
}