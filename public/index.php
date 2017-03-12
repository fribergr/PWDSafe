<?php
require_once("../config.inc.php");
require_once "../vendor/autoload.php";

// Load classes
spl_autoload_register(function ($class) {
        $f = dirname(__FILE__)."/../".str_replace("\\", "/", $class) . ".php";
        if (file_exists($f)) {
                require_once($f);
        }
});

// Wrapper for session stuff
$session = new DevpeakIT\PWDSafe\Session();
$session->start();

if ($session->isLoggedIn()) {
        // Routes for logged in users
        $routes = [
                "/" => "\DevpeakIT\PWDSafe\Callbacks\GroupsSpecificCallback",
                "/logout" => "\DevpeakIT\PWDSafe\Callbacks\LogoutCallback",
                "/changepwd" => "\DevpeakIT\PWDSafe\Callbacks\ChangePwdCallback",
                "/pwdfor/:number" => "\DevpeakIT\PWDSafe\Callbacks\PasswordForCallback",
                "/cred/:number/remove" => "\DevpeakIT\PWDSafe\Callbacks\CredRemoveCallback",
                "/cred/add" => "\DevpeakIT\PWDSafe\Callbacks\CredAddCallback",
                "/groups/create" => "\DevpeakIT\PWDSafe\Callbacks\GroupCreateCallback",
                "/groups/:number" => "\DevpeakIT\PWDSafe\Callbacks\GroupsSpecificCallback",
                "/groups" => "\DevpeakIT\PWDSafe\Callbacks\GroupsCallback",
                "/groups/:number/delete" => "\DevpeakIT\PWDSafe\Callbacks\GroupsDeleteCallback",
        ];
} else {
        // Routes for public users
        $routes = [
                "/" => "\DevpeakIT\PWDSafe\Callbacks\PreLogonFirstPageCallback",
                "/reg" => "\DevpeakIT\PWDSafe\Callbacks\PreLogonRegisterCallback"
        ];
}

Toro::serve($routes);
