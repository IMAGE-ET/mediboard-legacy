<?php /* $Id$ */

/**
 *  @package Mediboard
 *  @subpackage classes
 *  @author  Thomas Despoix
 *  @version $Revision$
 */
 
class CFTP {
  var $hostname = null;
  var $username = null;
  var $userpass = null;
  var $logs     = null;
  
  
  function logError($log) {
    $this->logs[] = "<strong>Error : </strong>$log";
  }

  function logStep($log) {
    $this->logs[] = "<strong>Step : </strong>$log";
  }
  
  function sendFile($source_file, $destination_file, $mode = FTP_BINARY) {
    // Set up basic connection
    $conn_id = ftp_connect($this->hostname);
    if (!$conn_id) {
      $this->logError("failed to $this->hostname");
      return false;
    } 
    
    $this->logStep("Connected to $this->hostname");

    // Login with username and password
    $login_result = ftp_login($conn_id, $this->username, $this->userpass);
    if (!$login_result) {
      $this->logError("Failed to login as user $this->username");
      return false;
    } 
    
    $this->logStep("Logged in as user $this->username");
    
    // Upload the file
    $upload = ftp_put($conn_id, $destination_file, $source_file, $mode);
    if (!$upload) {
      $this->logError("Failed to upload source file $source_file as destination file $destination_file");
      return false;
    } 
    
    $this->logStep("Source file $source_file succesfully uploaded as destination file $destination_file !!!");
    
    // close the FTP stream
    ftp_close($conn_id);
    return true;
  }
  
}

?>