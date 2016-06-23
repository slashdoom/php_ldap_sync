<?php

  $root = realpath(dirname(__FILE__));
  
  require_once(realpath($root.'/../config/config.php'));
  require_once(realpath($root.'/../lib/logging.php'));
  require_once(realpath($root.'/../lib/ldap.php'));
  
  touch(realpath($root.'/../log/_default.log'));
  $logger = new logger(realpath($root.'/../log/_default.log'),$logging_level);

  $ldap_users = array();
  $mysql_users = array();
  
  echo "\n\rstarting sync...\n\r";
  $logger->debug("starting sync...");
  
  $ldap_users = array_column(
                  ldap_get_members(
                    $ldap_fqdn,$ldap_port,$ldap_search_user,$ldap_search_pass,"CN=wifi_byod_access,OU=groups,OU=org,DC=ad,DC=slashdoom,DC=lan",$logging_level,realpath($root.'/../log/_ldap.log')
                  ),'username');
  $mysql_users = mysql_get_users($db_host,$db_rw_user,$db_rw_pass,$db_name,$logging_level,realpath($root.'/../log/_ldap.log'))

?>
