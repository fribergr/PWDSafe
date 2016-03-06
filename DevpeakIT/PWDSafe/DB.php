<?php
namespace DevpeakIT\PWDSafe;

use PDO;
use PDOException;

class DB
{
        private $db;
        private static $instance;

        /**
         * @brief Connect to the database (credentials defined in config.inc.php
         */
        private function __construct()
        {
                try {
                        $pdoparam = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
                        $this->db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_DB, DB_USER, DB_PASS, $pdoparam);
                        $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $ex) {
                        echo "Connection to database failed\n<br>";
                        die();
                }
        }

        private function __clone()
        {
        }

        /**
         * @brief Only create one instance (singleton)
         * @return PDO
         */
        public static function getInstance()
        {
                if (!(self::$instance instanceof self)) {
                        self::$instance = new self();
                }
                return self::$instance->db;
        }
}