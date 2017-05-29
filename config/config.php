<?php
  // Server settings
  $url = gethostname();
  // Database settings
  $db_rw_user  = 'vopt_rw';
  $db_rw_pass  = '';
  $db_ro_user  = 'vopt_ro';
  $db_ro_pass  = '';
  $db_host     = 'localhost';
  $db_name     = 'raddb';
  // Mail settings
  $smtp_server = 'smtp.example.com';
  $smtp_port   = '25';
  $smtp_user   = '';
  $smtp_pass   = '';
  $smtp_to     = 'person@example.com';
  $smtp_from   = 'person@example.com';
  // LDAP settings
  $ldap_fqdn        = 'ldap.example.com';
  $ldap_port        = 3268;
  $ldap_user_group  = 'cn=group,ou=corp,dc=example,dc=com';
  $ldap_search_user = 'ldap_browse@ldap.example.com';
  $ldap_search_pass = '';
?>
