<?php
/**
 * @brief Autoloader for classes in the project that should be tested
 */
spl_autoload_register(function ($class) {
        $f = dirname(__FILE__)."/../".str_replace("\\", "/", $class) . ".php";
        if (file_exists($f)) {
                require_once $f;
        }
});
