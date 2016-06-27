<?php
/************************************************************
* FILENAME:    mysql.php
* DESCRIPTION: mysql functions
* 
* AUTHOR:      Patrick K. Ryon (Slashdoom)
* LICENSE:     BSD 3-clause (see LICENSE file)
************************************************************/

  function mysql_get_users($db_host,$db_user,$db_pass,$db_name,$log_level,$log_file) {
 
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

    $dc_results = array();

    // define sql and query
    $db_sql_query="SELECT * FROM radcheck";
    $db_query=mysqli_query($db_conn_stat,$db_sql_query);
  
    // build array from query
    while($row=mysqli_fetch_array($db_query)) {
      $dc_results[]=$row['username'];
      $logger->debug($row['username']." found in db");
    }
  
    $db_conn_stat->close();
    return $dc_results;

  }

  function mysql_add_user($db_host,$db_user,$db_pass,$db_name,$username,$pass,$mail,$log_level,$log_file) {
    
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
    
    $db_sql_query = "INSERT INTO radcheck (username, attribute, op, value, mail)
                     VALUES (".$username.", 'Cleartext-Password', ':=', ".$pass.",".$mail.")";

    if ($db_conn_stat->query($db_sql_query) === TRUE) {
      $logger->debug($username." successfully create from mysql database ".$db_name);
    } else {
      $logger->error("error creating mysql record: " . $db_conn_stat->error);
      $db_conn_stat->close();
    }

    $db_conn_stat->close();
    
  }
  
  function mysql_remove_user($db_host,$db_user,$db_pass,$db_name,$username,$log_level,$log_file) {
    
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
    
    // define sql and query
    $db_sql_query="DELETE FROM radcheck WHERE username='".$username."'";
    
    // delete record from database
    if ($db_conn_stat->query($db_sql_query) === TRUE) {
      $logger->debug($username." successfully deleted from mysql database ".$db_name);
    }
    else {
      $logger->error("error deleting mysql record: " . $db_conn_stat->error);
      $db_conn_stat->close();
      return false;
    }

    $db_conn_stat->close();
    return true;
    
  }

?>
