<?php
use DevpeakIT\PWDSafe\DB;

require_once("../config.inc.php");
require_once "../vendor/autoload.php";

// Load classes
spl_autoload_register(function ($class) {
        $f = dirname(__FILE__) . "/../" . str_replace("\\", "/", $class) . ".php";
        if (file_exists($f)) {
                require_once($f);
        }
});
$session = new DevpeakIT\PWDSafe\Session();
$session->start();

// Show 404 for non-existing routes
ToroHook::add("404", function () {
        $loader = new Twig_Loader_Filesystem(dirname(__FILE__) . '/../views');
        $twig = new Twig_Environment($loader, []);
        echo $twig->render('static/404.html');
});

$container = new \DevpeakIT\PWDSafe\Container();
$container->setEncryption(new \DevpeakIT\PWDSafe\Encryption());
$container->setUser(new \DevpeakIT\PWDSafe\User());
$container->setFormchecker(new \DevpeakIT\PWDSafe\FormChecker());
$container->setDB(DB::getInstance());
$container->setCredentials(new \DevpeakIT\PWDSafe\Credentials($container->getDB()));

Toro::serve([
    "/" => "\DevpeakIT\PWDSafe\Callbacks\PreLogonFirstPageCallback",
    "/changepwd" => "\DevpeakIT\PWDSafe\Callbacks\ChangePwdCallback",
    "/logout" => "\DevpeakIT\PWDSafe\Callbacks\LogoutCallback",
    "/reg" => function () use ($container) {
            return new \DevpeakIT\PWDSafe\Callbacks\Api\PreLogonRegisterCallback($container);
    },
    "/groups" => "\DevpeakIT\PWDSafe\Callbacks\GroupsCallback",
    "/groups/:number" => "\DevpeakIT\PWDSafe\Callbacks\GroupsSpecificCallback",
    "/groups/:number/changename" => "\DevpeakIT\PWDSafe\Callbacks\GroupsChangeNameCallback",
    "/groups/:number/delete" => "\DevpeakIT\PWDSafe\Callbacks\GroupsDeleteCallback",
    "/groups/:number/share" => function () use ($container) {
            return new \DevpeakIT\PWDSafe\Callbacks\GroupsShareCallback($container);
    },
    "/groups/:number/unshare/:number" => function () use ($container) {
            return new \DevpeakIT\PWDSafe\Callbacks\GroupsUnshareCallback($container);
    },
    "/groups/create" => function () use ($container) {
            return new \DevpeakIT\PWDSafe\Callbacks\GroupCreateCallback($container);
    },
    "/cred/:number/remove" => "\DevpeakIT\PWDSafe\Callbacks\CredRemoveCallback",
    "/cred/add" => function () use ($container) {
            return new \DevpeakIT\PWDSafe\Callbacks\CredAddCallback($container);
    },
    "/pwdfor/:number" => "\DevpeakIT\PWDSafe\Callbacks\PasswordForCallback",
    "/api/pwdchg" => function () use ($container) {
            return new \DevpeakIT\PWDSafe\Callbacks\Api\PasswordChangeCallback($container);
    },

]);
