<?php

    require_once 'config.php';

/**
* Audit Log Model
*/

class AuditLog
{
    
    public $timestamp;
    public $user;
    public $message;

    static public function init($details) {
        $auditLog = new AuditLog;

        foreach ($details as $key => $value) {
            $auditLog->$key = $value;
        }

        return $auditLog;
    }


    static public function getAll() {
        global $conf;

        $user = $_SESSION['username'];
        $password = $_SESSION['password'];
        $curl = new Curl;
        
        $url = $conf['api_protocol'] . "://$user:$password@".$conf['api_url'] ."/audit";
        $response = $curl->get($url, $vars = array());
        $result = json_decode($response, true);
        
        $allMessages = array();
        
        foreach ($result as $entry) {
          $allMessages[] = self::init($entry);
        }
    
        return $allMessages;
  }


}

?>
