<?php
/**
 * Created by PhpStorm.
 * User: Robin
 * Date: 2017-03-12
 * Time: 13:33
 */

namespace DevpeakIT\PWDSafe\Callbacks;


use DevpeakIT\PWDSafe\DB;
use DevpeakIT\PWDSafe\GUI\Graphics;

class GroupsDeleteCallback
{
        public function get($id)
        {
                $sql = "SELECT groups.id, groups.name FROM groups
                        INNER JOIN usergroups ON usergroups.groupid = groups.id
                        INNER JOIN users ON users.id = usergroups.userid
                        WHERE users.id = :userid AND groups.id = :groupid";
                $stmt = DB::getInstance()->prepare($sql);
                $stmt->execute([
                    'userid' => $_SESSION['id'],
                    'groupid' => $id
                ]);
                $res = $stmt->fetchAll();
                if (count($res) === 1) {
                        $res = $res[0];
                        $graphics = new Graphics();
                        $graphics->showDeleteGroup($res['name'], $res['id']);
                } else {
                        $graphics = new Graphics();
                        $graphics->showUnathorized();
                }
        }

        public function post($id)
        {
                $sql = "SELECT groups.id FROM groups
                        INNER JOIN usergroups ON usergroups.groupid = groups.id
                        WHERE groups.id = :groupid AND usergroups.userid = :userid";
                $permission_stmt = DB::getInstance()->prepare($sql);
                $permission_stmt->execute([
                    'groupid' => $id,
                    'userid' => $_SESSION['id']
                ]);

                if ($permission_stmt->rowCount() > 0) {
                        $deleteenc_sql = "DELETE FROM encryptedcredentials WHERE credentialid IN (
                                            SELECT id FROM credentials WHERE groupid = :groupid
                                          )";
                        $deleteenc_stmt = DB::getInstance()->prepare($deleteenc_sql);
                        $deleteenc_stmt->execute(['groupid' => $id]);

                        $deletecred_sql = "DELETE FROM credentials WHERE groupid = :groupid";
                        $deletecred_stmt = DB::getInstance()->prepare($deletecred_sql);
                        $deletecred_stmt->execute(['groupid' => $id]);

                        $deleteug_sql = "DELETE FROM usergroups WHERE groupid = :groupid";
                        $deleteug_stmt = DB::getInstance()->prepare($deleteug_sql);
                        $deleteug_stmt->execute(['groupid' => $id]);

                        $deleteg_sql = "DELETE FROM groups WHERE id = :groupid";
                        $deleteg_stmt = DB::getInstance()->prepare($deleteg_sql);
                        $deleteg_stmt->execute(['groupid' => $id]);

                        echo json_encode([
                            'status' => 'OK'
                        ]);
                } else {
                        echo json_encode([
                            'status' => 'Fail',
                            'reason' => 'You are not authorized to delete this group'
                        ]);
                }
        }
}