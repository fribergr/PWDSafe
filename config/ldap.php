<?php

return [
    'enabled' => env('USE_LDAP', false),
    'basedn' => env('AD_USERCONTAINER', ''),
    'domain' => env('AD_DOM', ''),
    'server' => env('AD_SRV', ''),
];
