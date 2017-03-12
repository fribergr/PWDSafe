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

        public function showGroup($data, $groupid, $groupname)
        {
                echo $this->twig->render(
                    'group.html',
                    [
                        'data' => $data,
                        'loggedin' => true,
                        'loggedin_email' => $_SESSION['user'],
                        'groupid' => $groupid,
                        'groupname' => $groupname,
                        'primary' => $_SESSION['primarygroup']
                    ]
                );
        }

        public function showLogin($error = false)
        {
                echo $this->twig->render('login.html', ['error' => $error]);
        }

        public function showChangePwd()
        {
                echo $this->twig->render(
                    'changepwd.html',
                    [
                        'loggedin' => true,
                        'loggedin_email' => $_SESSION['user']
                    ]
                );
        }

        public function showCreateGroup()
        {
                echo $this->twig->render(
                    'groupcreate.html',
                    [
                        'loggedin' => true,
                        'loggedin_email' => $_SESSION['user']
                    ]
                );
        }

        public function showDeleteGroup($groupname, $groupid)
        {
                echo $this->twig->render(
                    'groupdelete.html',
                    [
                        'loggedin' => true, 'loggedin_email' => $_SESSION['user'],
                        'groupname' => $groupname,
                        'groupid' => $groupid
                    ]
                );
        }

        public function showUnathorized()
        {
                echo $this->twig->render(
                    'unauthorized.html',
                    [
                        'loggedin' => true,
                        'loggedin_email' => $_SESSION['user']
                    ]
                );
        }

        public function showShareGroup($data, $num, $groupname)
        {
                echo $this->twig->render(
                    'groupshare.html',
                    [
                        "data" => $data,
                        "groupid" => $num,
                        "groupname" => $groupname,
                        'loggedin' => true, 'loggedin_email' => $_SESSION['user']
                    ]
                );
        }
}
