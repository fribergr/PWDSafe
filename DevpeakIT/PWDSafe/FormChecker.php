<?php
namespace DevpeakIT\PWDSafe;

class FormChecker
{
        /**
         * @param $reqfields array with POST-fields we require for saving credentials
         * @return bool
         */
        public function checkRequiredFields($reqfields)
        {
                foreach ($reqfields as $fld) {
                        if (!isset($_POST[$fld]) || strlen(trim($_POST[$fld])) === 0) {
                                echo json_encode([
                                    'status' => 'Fail',
                                    'reason' => "Field '" . $fld . "' required but not set."
                                ]);
                                return false;
                        }
                }
                return true;
        }

        /**
         * @param $fld string field to look for in $_POST
         * @param $length int minimum required length
         * @return bool
         */
        public function checkFieldLength($fld, $length)
        {
                if (strlen(trim($_POST[$fld])) < $length) {
                        echo json_encode([
                                'status' => 'Fail',
                                'reason' => "Field '" . $fld . "' does not meet the length requirements"
                        ]);
                        return false;
                }
                return true;
        }
}