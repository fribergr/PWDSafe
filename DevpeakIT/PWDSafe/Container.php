<?php

namespace DevpeakIT\PWDSafe;

class Container
{
        private $encryption;

        public function getEncryption()
        {
                return $this->encryption;
        }

        public function setEncryption(Encryption $enc)
        {
                $this->encryption = $enc;
        }

        private $user;

        public function getUser()
        {
                return $this->user;
        }

        public function setUser(User $user)
        {
                $this->user = $user;
        }

        private $formchecker;

        public function getFormchecker()
        {
                return $this->formchecker;
        }

        public function setFormchecker(FormChecker $fc)
        {
                $this->formchecker = $fc;
        }

        private $db;

        public function getDB()
        {
                return $this->db;
        }

        public function setDB(\PDO $db)
        {
                $this->db = $db;
        }
}