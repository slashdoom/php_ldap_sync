<?php

  $root = realpath(dirname(__FILE__));
  
  include_once(realpath($root.'/../config/config.php'));
  include_once(realpath($root.'/../lib/logging.php'));
  include_once(realpath($root.'/../lib/ldap.php'));
  
  $logger = new logger(realpath($root.'/../log/_default.log'),'debug');
  
  echo "\n\rStarting sync...";
  $logger->debug("Starting sync...");
  
  print_r(ldap_get_members($ldap_fqdn,$ldap_port,$ldap_search_base,$ldap_search_user,$ldap_search_pass,"wifi_byod_access"));

?>
