<?php

  $root = realpath(dirname(__FILE__));
  
  require_once(realpath($root.'/../config/config.php'));
  require_once(realpath($root.'/../lib/logging.php'));
  require_once(realpath($root.'/../lib/ldap.php'));
  
  touch(realpath($root.'/../log/_default.log'));
  $logger = new logger(realpath($root.'/../log/_default.log'),$logging_level);
  
  echo "\n\rStarting sync...\n\r";
  $logger->debug("Starting sync...");
  
  print_r(ldap_get_members($ldap_fqdn,$ldap_port,$ldap_search_user,$ldap_search_pass,"CN=wifi_byod_access,OU=groups,OU=org,DC=ad,DC=slashdoom,DC=lan",$logging_level,realpath($root.'/../log/_ldap.log')));

?>
