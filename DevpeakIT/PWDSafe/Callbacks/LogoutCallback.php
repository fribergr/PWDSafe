<?php
namespace DevpeakIT\PWDSafe\Callbacks;

use DevpeakIT\PWDSafe\RequireAuthorization;

class LogoutCallback extends RequireAuthorization
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
