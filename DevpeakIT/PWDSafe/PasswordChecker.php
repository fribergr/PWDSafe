<?php
namespace DevpeakIT\PWDSafe;

use DevpeakIT\PWDSafe\Exceptions\AppException;

class PasswordChecker
{
        public static function checkPwdStrength($current, $old, $new1, $new2)
        {
                $errors = [];

                if ($current !== $old) {
                        $errors[] = "Field 'Old password' and current password does not match";
                }

                if ($new1 !== $new2) {
                        $errors[] = "Fields 'New password' and 'Repeat' does not match";
                }

                if (strlen(trim($new1)) < 8) {
                        $errors[] = "New password is too short";
                }

                if (count($errors) > 0) {
                    throw new AppException("Password strength check failed", 0, null, $errors);
                } else {
                    return true;
                }
        }
}