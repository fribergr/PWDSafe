<?php
namespace DevpeakIT\PWDSafe\Exceptions;

class AppException extends \Exception
{
    private $_errors;

    public function __construct($message, $code = 0, \Exception $previous = null, $errors = array())
    {
        parent::__construct($message, $code, $previous);

        if (count($errors) === 0) {
            $this->_errors = [$message];
        } else {
            $this->_errors = $errors;
        }
    }

    public function getErrors() {
        return $this->_errors;
    }
}
