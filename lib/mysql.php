<?php
/************************************************************
* FILENAME:    mysql.php
* DESCRIPTION: mysql functions
* 
* AUTHOR:      Patrick K. Ryon (Slashdoom)
* LICENSE:     BSD 3-clause (see LICENSE file)
************************************************************/

function mysql_get_users($db_host, $db_user, $db_pass, $db_name,$log_level,$log_file) {
 
  $root = realpath(dirname(__FILE__));
 
  require_once(realpath($root.'/../lib/logging.php'));

  // setup logging
  touch($log_file);
  $logger = new logger($log_file,$log_level);
  
  // connect to SQL server
  $logger->debug("connecting to mysql ".$db_host.",".$db_name);
  $db_conn_stat = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
 
  // check SQL connection
  if (mysqli_connect_errno()) {
    $logger->error("failed to connect to mysql: ".mysqli_connect_error());
    return false;
  }

  $dc_results_array = array();

  $db_query="SELECT * FROM radcheck";
  $db_results=mysqli_query($db_conn_stat,$db_query);
  $dc_results_array=mysqli_fetch_array($db_results);

  print_r($dc_results_array);

}
