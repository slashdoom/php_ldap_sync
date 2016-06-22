<?php

  $root = realpath(dirname(__FILE__));
  
  include_once(realpath($root.'/../config/config.php'));
  include_once(realpath($root.'/../lib/logging.php'));
  
  $logger = new logger(realpath($root.'/../log/_default.log'),"4");
  
  echo "\n\r\n\r";
  $logger->info("testing");
  echo "\n\r\n\r";
  
?>
