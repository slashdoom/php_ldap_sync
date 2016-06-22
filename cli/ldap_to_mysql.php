<?php

  $root = realpath(dirname(__FILE__));
  
  include_once(realpath($root.'/../config/config.php'));
  include_once(realpath($root.'/../lib/logging.php'));
  
  $logger = new logger(realpath($root.'/../log/_default.log'),'debug');
  
  echo "\n\rStarting sync...";
  $logger->debug("Starting sync...");
  
  
?>
