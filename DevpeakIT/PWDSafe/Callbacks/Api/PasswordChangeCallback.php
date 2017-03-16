<?php
namespace DevpeakIT\PWDSafe\Callbacks\Api;

use DevpeakIT\PWDSafe\FormChecker;
use DevpeakIT\PWDSafe\User;

class PasswordChangeCallback
{
        public function post()
        {
                FormChecker::checkRequiredFields(['username', 'old_password', 'new_password']);
                User::changePassword($_POST['username'], $_POST['old_password'], $_POST['new_password']);
        }
}