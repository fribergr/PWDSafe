<?php
namespace DevpeakIT\PWDSafe\GUI;

use Twig_Loader_Filesystem;
use Twig_Environment;

/**
 * Class Graphics
 * @brief Used for rendering some of the pages we use (or all?)
 * @package DevpeakIT\PWDSafe\GUI
 */
class Graphics
{
        protected $twig;

        public function __construct()
        {
                $loader = new Twig_Loader_Filesystem(dirname(__FILE__) . '/../../../views');
                $this->twig = new Twig_Environment($loader, []);
        }

        public function showGroup($data, $groupid)
        {
                echo $this->twig->render('group.html', ['data' => $data, 'loggedin' => true, 'groupid' => $groupid]);
        }

        public function showLogin($error = false)
        {
                echo $this->twig->render('login.html', ['error' => $error]);
        }

        public function showChangePwd()
        {
                echo $this->twig->render('changepwd.html', ['loggedin' => true]);
        }

        public function showCreateGroup()
        {
                echo $this->twig->render('groupcreate.html', ['loggedin' => true]);
        }
}
