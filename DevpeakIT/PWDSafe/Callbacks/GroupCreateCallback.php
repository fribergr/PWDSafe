<?php
namespace DevpeakIT\PWDSafe\Callbacks;

use DevpeakIT\PWDSafe\FormChecker;
use DevpeakIT\PWDSafe\Group;
use DevpeakIT\PWDSafe\GUI\Graphics;
use DevpeakIT\PWDSafe\RequireAuthorization;
use DevpeakIT\PWDSafe\Traits\ContainerInject;

class GroupCreateCallback extends RequireAuthorization
{
        use ContainerInject;
        public function get()
        {
                $graphics = new Graphics();
                $graphics->showCreateGroup();
        }

        public function post()
        {
                $fc = new FormChecker();
                if ($fc->checkRequiredFields(['groupname']) && $fc->checkFieldLength('groupname', 1)) {
                        $grp = new Group($this->container);
                        $group = $grp->create($_POST['groupname']);
                        $group->givePermission($_SESSION['id']);
                        echo json_encode(['status' => "OK", "groupid" => $group->id]);
                }
        }
}