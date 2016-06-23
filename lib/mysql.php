<?php
/************************************************************
* FILENAME:    mysql.php
* DESCRIPTION: mysql functions for php
* 
* AUTHOR:      Patrick K. Ryon (Slashdoom)
* LICENSE:     BSD 3-clause (see LICENSE file)
************************************************************/

function mysql_get_users($ldap_fqdn,$ldap_port,$ldap_user,$ldap_pass,$search_group,$log_level,$log_file) {
 
  $root = realpath(dirname(__FILE__));
 
  require_once(realpath($root.'/../lib/logging.php'));

  // setup logging
  touch($log_file);
  $logger = new logger($log_file,$log_level);

}
