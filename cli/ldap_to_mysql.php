#!/usr/bin/php
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
  
  // get all users in group from ldap
  echo "reading ldap users...\n\r";
  $logger->debug("reading ldap users...");
  $ldap_users = ldap_get_members(
      $ldap_fqdn,$ldap_port,$ldap_search_user,$ldap_search_pass,$ldap_user_group,$logging_level,realpath($root.'/../log/_ldap.log')
                                );
  // get all user in mysql db
  echo "reading mysql users...\n\r";
  $logger->debug("reading mysql users...");
  $mysql_users = mysql_get_users($db_host,$db_rw_user,$db_rw_pass,$db_name,$logging_level,realpath($root.'/../log/_mysql.log'));

  // get users in ldap but not mysql (adds)
  echo "running diff of ldap against mysql users...\n\r";
  $logger->debug("running diff of ldap against mysql users...");
  $diff_add = array_diff(array_column($ldap_users,'username'), $mysql_users);
  // get users in mysql but not ldap (removes)
  echo "running diff of mysql against ldap users...\n\r";
  $logger->debug("running diff of mysql against ldap users...");
  $diff_rem = array_diff($mysql_users, array_column($ldap_users,'username'));

  echo "\r\nstarting user removals...\r\n";
  $logger->debug("starting user removals...");
  foreach ($diff_rem as $rem_user) {
    echo "removing user ".$rem_user."\r\n";
    $logger->debug("removing user ".$rem_user);
    
    // remove user from mysql db
    mysql_remove_user($db_host,$db_rw_user,$db_rw_pass,$db_name,$rem_user,$logging_level,realpath($root.'/../log/_mysql.log'));
  }

  echo "\r\nstarting user adds...\r\n";
  $logger->debug("starting user adds...");
  foreach ($diff_add as $add_user) {
    echo "adding user ".$add_user."\r\n";
    $logger->debug("adding user ".$add_user);
    
    // generate 'random' password
    $add_user_pass = random_str($pass_len, $pass_char);
    
    // get e-mail address from ldap array
    $key = array_search($add_user, array_column($ldap_users, 'username'));
    $add_user_mail = $ldap_users[$key]['mail'];
    
    // add user to mysql db
    mysql_add_user($db_host,$db_rw_user,$db_rw_pass,$db_name,$add_user,$add_user_pass,$add_user_mail,$logging_level,realpath($root.'/../log/_mysql.log'));
  }
  
  echo "...done.\r\n";
  $logger->debug("...done.");

?>
