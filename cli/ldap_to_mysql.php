<?php
  $root1 = realpath($_SERVER["DOCUMENT_ROOT"]);
  $root2 = realpath(dirname(__FILE__));
  
  echo "\r\n";
  echo $root1;
  echo "\r\n";
  echo $root2;
  //include_once("../config/config.php");
  
?>
