<?php
namespace DevpeakIT\PWDSafe;

use DevpeakIT\PWDSafe\GUI\Graphics;
use ToroHook;

class RequireAuthorization
{
        public function __construct()
        {
                ToroHook::add("before_handler", function () {
                        $session = new Session();
                        if (!$session->isLoggedIn()) {
                                $graphics = new Graphics();
                                $graphics->showLoginRequired();
                                die();
                        }
                });
        }
}