<?php
namespace DevpeakIT\PWDSafe\Authentication;

use DevpeakIT\PWDSafe\Exceptions\AppException;

class LDAPAuthentication
{
        public static function login($user, $pass)
        {
                if (REQCERT) {
                        putenv('LDAPTLS_CACERT=' . CERTFILE);
                } else {
                        putenv('LDAPTLS_REQCERT=never');
                }

                $upn = $user . "@" . AD_DOM;

                $conn = ldap_connect(AD_SRV);
                if (!$conn) {
                        throw new AppException("Could not connect to LDAP-server");
                }

                $bind = ldap_bind($conn, $upn, $pass);

                if ($bind) {
                        $s = ldap_search(
                            $conn,
                            AD_USERCONTAINER,
                            "(|(userPrincipalName=$upn))",
                            array("cn", "dn", "userPrincipalName", "samaccountname")
                        );
                        if ($s === false) {
                                throw new AppException("Could not search AD");
                        }

                        $info = ldap_get_entries($conn, $s);
                        if ($info === false) {
                                throw new AppException("LDAP get entries failed");
                        }

                        return count($info) > 0;
                } else {
                        return false;
                }
        }

        public static function serviceping($host, $port = 389, $timeout = 1)
        {
                $host = str_replace("ldaps://", "", $host);
                $host = str_replace("ldap://", "", $host);
                $op = fsockopen($host, $port, $errno, $errstr, $timeout);
                if (!$op) {
                        return false;
                } else {
                        fclose($op);
                        return true;
                }
        }
}