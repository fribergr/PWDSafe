<?php

namespace DevpeakIT\PWDSafe\Callbacks\Api;

use DevpeakIT\PWDSafe\Container;

class PasswordChangeCallback
{
        protected $container;

        public function __construct(Container $container)
        {
                $this->container = $container;
        }

        public function post()
        {
                $formchecker = $this->container->getFormchecker();
                if ($formchecker->checkRequiredFields(['username', 'old_password', 'new_password'])) {
                        $user = $this->container->getUser();
                        $user->changePassword($_POST['username'], $_POST['old_password'], $_POST['new_password']);
                }
        }
}