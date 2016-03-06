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

        public function showLoggedin($data)
        {
                echo $this->twig->render('loggedin.html', ['data' => $data, 'loggedin' => true]);
        }

        public function showLogin($error = false)
        {
                echo $this->twig->render('login.html', ['error' => $error]);
        }
}
