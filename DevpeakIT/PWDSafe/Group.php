<?php
namespace DevpeakIT\PWDSafe;

class Group
{
        public $name;
        public $id;

        public function __construct($data)
        {
                $this->name = $data['name'];
                $this->id = $data['id'];
        }

        public static function create($name)
        {
                $sql = "INSERT INTO groups (name) VALUES(:name)";
                $stmt = DB::getInstance()->prepare($sql);
                $stmt->execute(['name' => $name]);
                return new self(['name' => $name, 'id' => DB::getInstance()->lastInsertId()]);
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
}