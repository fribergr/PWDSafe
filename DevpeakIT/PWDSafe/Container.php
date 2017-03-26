<?php

namespace DevpeakIT\PWDSafe;

class Container
{
        private $encryption;

        /**
         * @return Encryption
         */
        public function getEncryption()
        {
                return $this->encryption;
        }

        public function setEncryption(Encryption $enc)
        {
                $this->encryption = $enc;
        }

        private $user;

        /**
         * @return User
         */
        public function getUser()
        {
                return $this->user;
        }

        public function setUser(User $user)
        {
                $this->user = $user;
        }

        private $formchecker;

        /**
         * @return FormChecker
         */
        public function getFormchecker()
        {
                return $this->formchecker;
        }

        public function setFormchecker(FormChecker $fc)
        {
                $this->formchecker = $fc;
        }

        private $db;

        /**
         * @return \PDO
         */
        public function getDB()
        {
                return $this->db;
        }

        public function setDB(\PDO $db)
        {
                $this->db = $db;
        }
}