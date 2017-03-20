<?php
namespace DevpeakIT\PWDSafe\Callbacks;

use DevpeakIT\PWDSafe\Group;

class GroupsUnshareCallback
{
        public function post($groupid = null, $userid = null)
        {
                if (is_null($groupid) || is_null($userid) || !is_numeric($groupid) || !is_numeric($userid)) {
                        echo json_encode([
                            'status' => 'Fail',
                            'reason' => 'Missing groupid or userid'
                        ]);
                        return;
                }

                // Check access
                $grp = new Group();
                $grp->id = $groupid;

                if (!$grp->checkAccess($_SESSION['id'])) {
                        echo json_encode([
                            'status' => 'Fail',
                            'reason' => 'Unauthorized'
                        ]);
                        return;
                }

                // Access OK, remove credentials for this user in this group
                $grp->deleteCredentialsForUser($userid);

                // Remove user from group
                $grp->removePermission($userid);

                echo json_encode([
                    'status' => 'OK'
                ]);
        }
}