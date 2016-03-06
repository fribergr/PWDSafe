<?php
namespace DevpeakIT\PWDSafe\Callbacks;

use DevpeakIT\PWDSafe\DB;
use DevpeakIT\PWDSafe\GUI\Graphics;

class FirstPageCallback
{
        /**
         * @brief Show logged in page
         */
        public function get()
        {
                $graphics = new Graphics();
                $db = DB::getInstance();
                $sql = "SELECT * FROM credentials WHERE userid = :userid";
                $stmt = $db->prepare($sql);
                $stmt->execute(['userid' => $_SESSION['id']]);
                $data = $stmt->fetchAll();
                $graphics->showLoggedin($data);
        }
}
