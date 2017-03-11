<?php
namespace DevpeakIT\PWDSafe;

class FormChecker
{
        /**
         * @param $reqfields array with POST-fields we require for saving credentials
         */
        public static function checkRequiredFields($reqfields)
        {
                foreach ($reqfields as $fld) {
                        if (!isset($_POST[$fld])) {
                                echo json_encode([
                                    'status' => 'Fail',
                                    'reason' => "Field '" . $fld . "' required but not set."
                                ]);
                                die();
                        }
                }
        }
}