<?php

  require_once 'model/Persistable.php';
  require_once 'config.php';
  
/**
* New Member model
*/
class NewMember implements Persistable
{
  
  public $id;
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
    global $conf;
    
    $user = $_SESSION['username'];
    $password = $_SESSION['password'];
    $curl = new Curl;
    
    $url = $conf['api_protocol'] . "://$user:$password@".$conf['api_url'] ."/newmembers";
    $response = $curl->get($url, $vars = array());
    $result = json_decode($response, true);
    
    $allNew = array();
    
    foreach ($result as $member) {
      $allNew[] = self::init($member);
    }
    
    return $allNew;
  }
  
  public function delete() {
    global $conf;

    $user = $_SESSION['username'];
    $password = $_SESSION['password'];
    $curl = new Curl;

    $url = $conf['api_protocol'] . "://$user:$password@".$conf['api_url'] ."/newmembers/" . $this->id;
    $response = $curl->delete($url, $vars = array());

    if ($response->headers['Status'] != "200 OK") {
      return false;
    } else {
      return json_decode($response, true);
    }
  }
  
  public function save() {
    //only to create a new member - doesnt support updates atm...
    global $conf;
    
    if ($this->id == null) {   
      $details = get_object_vars($this);
      
      // $user = $_SESSION['username'];
      // $password = $_SESSION['password'];
      $curl = new Curl;

      $url = $conf['api_protocol'] . "://".$conf['api_url'] ."/newmembers";
      $response = $curl->post($url, json_encode($details));

      if ($response->headers['Status'] != "200 OK") {
        return false;
      } else {
        return true;
      }
    } else {
      return false;
    }
  }
  
  public function activate(){
    
  }
  
}
?>