<?php

  require_once 'model/Persistable.php';
  require_once 'config.php';
  
/**
* New Member model
*/
class NewMember implements Persistable
{
  
  public $firstname;
  public $lastname;
  public $username;
  public $studentnumber;
  public $email;
  
  static public function init($details) {
    $newMember = new NewMember;
    
    foreach ($details as $key => $value) {
      $newMember->$key = $value;
    }
    
    return $newMember;
    
  }
  
  static public function get($id){
    global $conf;
    
    $user = $_SESSION['username'];
    $password = $_SESSION['password'];
    $curl = new Curl;
    
    $url = $conf['api_protocol'] . "://$user:$password@".$conf['api_url'] ."/newmembers/$id";
    $response = $curl->get($url, $vars = array());
    $result = json_decode($response, true);
    
    if ($response->headers['Status'] != "200 OK") {
      return null;
    } else {
      return self::init($result);
    }
  }
  
  static public function getAll() {
    
  }
  
  public function delete() {
    
  }
  
  public function save() {
    //only to create a new member - doesnt support updates atm...
  }
  
  public function activate(){
    
  }
  
}
?>