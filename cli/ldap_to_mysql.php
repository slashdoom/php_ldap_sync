<?php

  $root = realpath(dirname(__FILE__));
  
  require_once(realpath($root.'/../config/config.php'));
  require_once(realpath($root.'/../lib/logging.php'));
  require_once(realpath($root.'/../lib/ldap.php'));
  require_once(realpath($root.'/../lib/mysql.php'));
  require_once(realpath($root.'/../lib/misc.php'));
  
  touch(realpath($root.'/../log/_default.log'));
  $logger = new logger(realpath($root.'/../log/_default.log'),$logging_level);

  $ldap_users = array();
  $mysql_users = array();
  
  echo "\n\rstarting sync...\n\r";
  $logger->debug("starting sync...");
  
  $ldap_users = ldap_get_members(
      $ldap_fqdn,$ldap_port,$ldap_search_user,$ldap_search_pass,$ldap_user_group,$logging_level,realpath($root.'/../log/_ldap.log')
                                );
  $mysql_users = mysql_get_users($db_host,$db_rw_user,$db_rw_pass,$db_name,$logging_level,realpath($root.'/../log/_mysql.log'));

  $diff_add = array_diff(array_column($ldap_users,'username'), $mysql_users);
  $diff_rem = array_diff($mysql_users, array_column($ldap_users,'username'));

  echo " \r\nldap raw: \r\n";
  print_r($ldap_users);
  echo " \r\nldap: \r\n";
  print_r(array_column($ldap_users,'username'));
  echo " \r\nmysql: \r\n";
  print_r($mysql_users);
  echo " \r\nadd: \r\n";
  print_r($diff_add);
  echo " \r\nrem: \r\n";
  print_r($diff_rem);
  
  echo "\r\nStarting removals...\r\n";
  foreach ($diff_rem as $rem_user) {
    if (mysql_remove_user($db_host,$db_rw_user,$db_rw_pass,$db_name,$rem_user,$log_level,realpath($root.'/../log/_mysql.log')) === TRUE) {
      echo "\r\n".$rem_user." removed\r\n\";
    }
  }
  
?>
