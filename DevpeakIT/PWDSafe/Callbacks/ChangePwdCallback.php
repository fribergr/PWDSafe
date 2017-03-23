<?php
namespace DevpeakIT\PWDSafe\Callbacks;

use DevpeakIT\PWDSafe\DB;
use DevpeakIT\PWDSafe\Encryption;
use DevpeakIT\PWDSafe\Exceptions\AppException;
use DevpeakIT\PWDSafe\FormChecker;
use DevpeakIT\PWDSafe\GUI\Graphics;
use DevpeakIT\PWDSafe\PasswordChecker;
use DevpeakIT\PWDSafe\RequireAuthorization;
use DevpeakIT\PWDSafe\User;

class ChangePwdCallback extends RequireAuthorization
{
        public function get()
        {
                $graphics = new Graphics();
                $graphics->showChangePwd();
        }

        public function post()
        {
                if (FormChecker::checkRequiredFields(['oldpwd', 'newpwd1', 'newpwd2'])) {

                        try {
                                PasswordChecker::checkPwdStrength(
                                    $_SESSION['pass'],
                                    $_POST['oldpwd'],
                                    $_POST['newpwd1'],
                                    $_POST['newpwd2']
                                );
                        } catch (AppException $ex) {
                                echo json_encode([
                                    'status' => 'Fail',
                                    'reason' => implode(". ", $ex->getErrors())
                                ]);

                                return;
                        }

                        User::changePassword($_SESSION['user'], $_SESSION['pass'], $_POST['newpwd1']);
                }
        }
}
