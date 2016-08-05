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
    private $log_fh_level;
    private $log_ch_level;
    // class constructor, file handler defaults to WARNING level, console hander defaults to DEBUG level
    // beaware!  no file checking!
    function __construct($log_file='./_default.log',$log_fh_level=2,$log_ch_level=4) {
    // get log file if specified
    $this->log_file = $log_file;
    // check for fh log level by string names
    if (is_string($log_fh_level)) {
        // valid string level found
        switch(strtolower($log_fh_level)) {
            case 'debug':
                $this->log_fh_level = 4;
                break;
            case 'info':
                $this->log_fh_level = 3;
                break;
            case 'warning':
                $this->log_fh_level = 2;
                break;
            case 'error':
                $this->log_fh_level = 1;
                break;
            case 'critical':
                $this->log_fh_level = 0;
                break;
            // invalid level, use WARNING default level
            default:
                $this->log_fh_level = 2;
        }
    }
    // check for fh log level by integar values
    else {
        // valid 0-4 level found
        if ($log_fh_level >= 0 && $log_fh_level <= 4) {
            $this->log_fh_level = $log_fh_level;
        }
        // invalid level, use WARNING default level
        else {
            $this->$log_fh_level = 2;
        }
    }
    
    // check for ch log level by string names
    if (is_string($log_ch_level)) {
    // valid string level found
        switch(strtolower($log_ch_level)) {
            case 'debug':
                $this->log_ch_level = 4;
                break;
            case 'info':
                $this->log_ch_level = 3;
                break;
            case 'warning':
                $this->log_ch_level = 2;
                break;
            case 'error':
                $this->log_ch_level = 1;
                break;
            case 'critical':
                $this->log_ch_level = 0;
                break;
            // invalid level, use DEFAULT default level
            default:
                $this->log_ch_level = 4;
            }
        }
        // check for ch log level by integar values
        else {
            // valid 0-4 level found
            if ($log_ch_level >= 0 && $log_ch_level <= 4) {
                $this->log_ch_level = $log_ch_level;
            }
            // invalid level, use DEFAULT default level
            else {
                $this->$log_ch_level = 4;
            }
        }
    }
  
    // function to write log messgaes to file 
    private function fh_log($level = 'INFO', $msg) {
        // get time in YYYY-MM-DD HH:MM:SS format
        $datetime = date("Y-m-d H:i:s");
    
        // append new logs to file
        $file = fopen($this->log_file, 'a');
        fwrite($file,$datetime." [".$level."] - ".$msg."\n");
    
        // close file
        fclose($file);
    }
    
    // function to echo log messages to console 
    private function ch_log($level = 'INFO', $msg) {
        // get time in YYYY-MM-DD HH:MM:SS format
        $datetime = date("Y-m-d H:i:s");
    
        // echo to cli
        echo $datetime." [".$level."] - ".$msg."\n";
    }
    
    // DEBUG level log function
    function debug($msg) {
        // check logging level, only write if DEBUG or higher
        if ($this->log_fh_level >= 4) {
            $this->fh_log("DEBUG", $msg);
        }
        if ($this->log_ch_level >= 4) {
            $this->ch_log("DEBUG", $msg);
        }
    }
    
    // INFO level log function
    function info($msg) {
        // check logging level, only write if INFO or higher
        if ($this->log_fh_level >= 3) {
            $this->fh_log("INFO", $msg);
        }
        if ($this->log_ch_level >= 3) {
            $this->ch_log("INFO", $msg);
        }
    }
    
    // WARNING level log function
    function warning($msg) {
        // check logging level, only write if WARNING or higher
        if ($this->log_fh_level >= 2) {
            $this->fh_log("WARNING", $msg);
        }
        if ($this->log_ch_level >= 2) {
            $this->ch_log("WARNING", $msg);
        }
    }
    
    // ERROR level log function
    function error($msg) {
        // check logging level, only write if ERROR or higher
        if ($this->log_fh_level >= 1) {
            $this->fh_log("ERROR", $msg);
        }
        if ($this->log_ch_level >= 1) {
            $this->ch_log("ERROR", $msg);
        }
    }
    
    // CRITICAL level log function
    function critical($msg) {
        // check logging level, only write if CRITICAL or higher
        if ($this->log_fh_level >= 0) {
            $this->fh_log("CRITICAL", $msg);
        }
        if ($this->log_ch_level >= 0) {
            $this->ch_log("CRITICAL", $msg);
        }
    }
}
?>
