<?php
namespace DevpeakIT\PWDSafe;

class Group
{
        public $name;
        public $id;

        public function setAll($data)
        {
                $this->name = $data['name'];
                $this->id = $data['id'];
        }

        public static function create($name)
        {
                $sql = "INSERT INTO groups (name) VALUES(:name)";
                $stmt = DB::getInstance()->prepare($sql);
                $stmt->execute(['name' => $name]);
                $self = new self();
                $self->setAll(['name' => $name, 'id' => DB::getInstance()->lastInsertId()]);
                return $self;
        }

        public function givePermission($userid)
        {
                $sql = "INSERT INTO usergroups (userid, groupid) VALUES (:userid, :groupid)";
                $stmt = DB::getInstance()->prepare($sql);
                $stmt->execute([
                    'userid' => $userid,
                    'groupid' => $this->id
                ]);
        }

        public function checkAccess($userid)
        {
                $access_sql = "SELECT groups.id, groups.name FROM groups
                               INNER JOIN usergroups ON usergroups.groupid = groups.id
                               INNER JOIN users ON users.id = usergroups.userid
                               WHERE usergroups.userid = :userid AND usergroups.groupid = :groupid";
                $access_stmt = DB::getInstance()->prepare($access_sql);
                $access_stmt->execute([
                    'userid' => $userid,
                    'groupid' => $this->id
                ]);
                return $access_stmt->rowCount() !== 0;
        }
}