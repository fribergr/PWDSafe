<?php
namespace DevpeakIT\PWDSafe\Callbacks;

class LogoutCallback
{
        /**
         * @brief Wrapper for logging out (destroying session)
         */
        public function get()
        {
                session_destroy();
                header("Location: /");
        }
}
