<?php

namespace DevpeakIT\PWDSafe;

use DevpeakIT\PWDSafe\Traits\ContainerInject;

class Group
{
        use ContainerInject;

        public $name;
        public $id;

        public function setAll($data)
        {
                $this->name = $data['name'];
                $this->id = $data['id'];
        }

        public function create($name)
        {
                $sql = "INSERT INTO groups (name) VALUES(:name)";
                $stmt = $this->container->getDB()->prepare($sql);
                $stmt->execute(['name' => $name]);
                $this->setAll(['name' => $name, 'id' => $this->container->getDB()->lastInsertId()]);
                return $this;
        }

        public function givePermission($userid)
        {
                $sql = "INSERT INTO usergroups (userid, groupid) VALUES (:userid, :groupid)";
                $stmt = $this->container->getDB()->prepare($sql);
                $stmt->execute([
                    'userid' => $userid,
                    'groupid' => $this->id
                ]);
        }

        public function removePermission($userid)
        {
                $sql = "DELETE FROM usergroups WHERE groupid = :groupid AND userid = :userid LIMIT 1";
                $stmt = $this->container->getDB()->prepare($sql);
                $stmt->execute([
                    'groupid' => $this->id,
                    'userid' => $userid
                ]);
        }

        public function deleteCredentialsForUser($userid)
        {
                $sql = "DELETE FROM encryptedcredentials
                        WHERE userid = :userid AND credentialid IN (
                          SELECT id FROM credentials WHERE groupid = :groupid
                        )";
                $stmt = $this->container->getDB()->prepare($sql);
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
                $access_stmt = $this->container->getDB()->prepare($access_sql);
                $access_stmt->execute([
                    'userid' => $userid,
                    'groupid' => $this->id
                ]);
                return $access_stmt->rowCount() !== 0;
        }

        public function getName()
        {
                if (!is_null($this->name)) {
                        return $this->name;
                }

                $sql = "SELECT name FROM groups WHERE id = :groupid";
                $stmt = $this->container->getDB()->prepare($sql);
                $stmt->execute(['groupid' => $this->id]);
                $this->name = $stmt->fetchColumn();
                return $this->name;
        }

        private function getMembers()
        {
                $sql = "SELECT users.id, users.email FROM users
                        INNER JOIN usergroups ON usergroups.userid = users.id
                        WHERE usergroups.groupid = :groupid";
                $stmt = $this->container->getDB()->prepare($sql);
                $stmt->execute([
                    'groupid' => $this->id
                ]);

                return $stmt->fetchAll();
        }

        public function getMembersExcept($userid)
        {
                $members = $this->getMembers();
                return array_filter($members, function ($element) use ($userid) {
                        return ($element['id'] != $userid);
                });
        }
}