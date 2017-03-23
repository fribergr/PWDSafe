<?php
namespace DevpeakIT\PWDSafe\Callbacks;

use DevpeakIT\PWDSafe\FormChecker;
use DevpeakIT\PWDSafe\Group;
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
                if (FormChecker::checkRequiredFields(['groupname']) && FormChecker::checkFieldLength('groupname', 1)) {
                        $group = Group::create($_POST['groupname']);
                        $group->givePermission($_SESSION['id']);
                        echo json_encode(['status' => "OK", "groupid" => $group->id]);
                }
        }
}