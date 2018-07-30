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

        public function showSecurityCheck($data)
        {
                echo $this->twig->render(
                    'securitycheck.html',
                    [
                        'data' => $data,
                        'loggedin' => true,
                        'loggedin_email' => $_SESSION['user'],
                        'loggedin_ldap' => $_SESSION['ldap'],
                        'primary' => $_SESSION['primarygroup']
                    ]
                );
        }

        public function showSearch($data)
        {
            echo $this->twig->render(
                'search.html',
                [
                    'data' => $data,
                    'loggedin' => true,
                    'loggedin_email' => $_SESSION['user'],
                    'loggedin_ldap' => $_SESSION['ldap'],
                    'primary' => $_SESSION['primarygroup']
                ]
            );
        }

        public function showGroup($data, $groupid, $groupname)
        {
                echo $this->twig->render(
                    'group.html',
                    [
                        'data' => $data,
                        'loggedin' => true,
                        'loggedin_email' => $_SESSION['user'],
                        'loggedin_ldap' => $_SESSION['ldap'],
                        'groupid' => $groupid,
                        'groupname' => $groupname,
                        'primary' => $_SESSION['primarygroup']
                    ]
                );
        }

        public function showLogin($error = false)
        {
                echo $this->twig->render('login.html', ['error' => $error, 'ldap' => USE_LDAP]);
        }

        public function showChangePwd()
        {
                echo $this->twig->render(
                    'changepwd.html',
                    [
                        'loggedin' => true,
                        'loggedin_email' => $_SESSION['user'],
                        'loggedin_ldap' => $_SESSION['ldap'],
                    ]
                );
        }

        public function showCreateGroup()
        {
                echo $this->twig->render(
                    'groupcreate.html',
                    [
                        'loggedin' => true,
                        'loggedin_email' => $_SESSION['user'],
                        'loggedin_ldap' => $_SESSION['ldap'],
                    ]
                );
        }

        public function showDeleteGroup($groupname, $groupid)
        {
                echo $this->twig->render(
                    'groupdelete.html',
                    [
                        'loggedin' => true,
                        'loggedin_email' => $_SESSION['user'],
                        'loggedin_ldap' => $_SESSION['ldap'],
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
                        'loggedin_email' => $_SESSION['user'],
                        'loggedin_ldap' => $_SESSION['ldap'],
                    ]
                );
        }

        public function showShareGroup($data, $num, $groupname)
        {
                echo $this->twig->render(
                    'group/share.html',
                    [
                        "data" => $data,
                        "groupid" => $num,
                        "groupname" => $groupname,
                        'loggedin' => true,
                        'loggedin_email' => $_SESSION['user'],
                        'loggedin_ldap' => $_SESSION['ldap'],
                    ]
                );
        }

        public function showLoginRequired()
        {
                echo $this->twig->render('static/login_required.html');
        }
}
