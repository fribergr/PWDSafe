<?php
/**
 * Define parameters for access to MySQL-database
 */
define("DB_HOST", "localhost");
define("DB_USER", "");
define("DB_DB", "");
define("DB_PASS", "");

// LDAP
define("USE_LDAP", false); // Enabling this will disable registration and password change
define("AD_SRV", ""); // Include ldap:// or ldaps://
define("AD_DOM", ""); // The upn will be username@AD_DOM
define("AD_USERCONTAINER", ""); // Point to OU of users
define("REQCERT", false); // If we should try to use a certificate for ldaps-connection
define("CERTFILE", ""); // Path to that file