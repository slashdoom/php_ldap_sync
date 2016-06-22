<?php

/************************************************************
* FILENAME:    logging.php
* DESCRIPTION: simple logging class for php
* 
* AUTHOR:      Patrick K. Ryon (Slashdoom)
* LICENSE:     BSD 3-clause (see LICENSE file)
************************************************************/

class logger {
  private $log_file;
  private $log_level;
  // class constructor, defaults to WARNING level
  // beaware!  no file checking!
  function __construct($log_file='./_default.log',$log_level=2) {
    // get log file if specified
    $this->log_file = $log_file;
    // check for log level by string names
    if (is_string($log_level)) {
      // valid string level found
      switch(strtolower($log_level)) {
        case 'debug':
          $this->log_level = 4;
          break;
        case 'info':
          $this->log_level = 3;
          break;
        case 'warning':
          $this->log_level = 2;
          break;
        case 'error':
          $this->log_level = 1;
          break;
        case 'critical':
          $this->log_level = 0;
          break;
        // invalid level, use WARNING default level
        default:
          $this->log_level = 2;
      }
    }
    // check for log level by integar values
    else {
      // valid 0-4 level found
      if ($log_level >= 0 && $log_level <= 4) {
        $this->log_level = $log_level;
      }
      // invalid level, use WARNING default level
      else {
        $this->log_level = 2;
      }
    }
  }
  
  // function to write actual log files
  private function log($level = 'INFO', $msg) {
    // get time in YYYY-MM-DD HH:MM:SS format
    $datetime = date("Y-m-d H:i:s");
    
    // append new logs to file
    $file = fopen($this->log_file, 'a');
    fwrite($file,$datetime." [".$level."] - ".$msg."\n");
    
    // close file
    fclose($file);
  }
  // DEBUG level log function
  function debug($msg) {
    // check logging level, only write if DEBUG or higher
    if ($this->log_level >= 4) {
      $this->log("DEBUG", $msg);
    }
	}
  // INFO level log function
  function info($msg) {
    // check logging level, only write if INFO or higher
    if ($this->log_level >= 3) {
      $this->log("INFO", $msg);
    }
	}
  // WARNING level log function
	function warning($msg) {
    // check logging level, only write if WARNING or higher
	  if ($this->log_level >= 2) {
      $this->log("WARNING", $msg);
    }
  }
  // ERROR level log function
  function error($msg) {
    // check logging level, only write if ERROR or higher
	  if ($this->log_level >= 1) {
      $this->log("ERROR", $msg);
    }
  }
  // CRITICAL level log function
  function critical($msg) {
    // check logging level, only write if CRITICAL or higher
	  if ($this->log_level >= 0) {
      $this->log("CRITICAL", $msg);
    }
  }
  // function for bulk logging
  function bulk_log($level,$msg) {
    if (is_string($level)) {
    // valid string level found
      switch(strtolower($level)) {
        case 'debug':
          $rlevel = 4;
          break;
        case 'info':
          $rlevel = 3;
          break;
        case 'warning':
          $rlevel = 2;
          break;
        case 'error':
          $rlevel = 1;
          break;
        case 'critical':
          $rlevel = 0;
          break;
        // invalid level, use WARNING default level
        default:
          $rlevel = 2;
      }
    }
    // check for log level by integar values
    else {
      // valid 0-4 level found
      if ($level >= 0 && $level <= 4) {
        $rlevel = $level;
      }
      // invalid level, use WARNING default level
      else {
        $rlevel = 2;
      }
    }
    // call log functions based on level
    switch($rlevel) {
      case 4:
        $this->debug($msg);
        break;
      case 3:
        $this->info($msg);
        break;
      case 2:
        $this->warning($msg);
        break;
      case 1:
        $this->error($msg);
        break;
      case 0:
        $this->critical($msg);
        break;
    }
  }
}
?>
