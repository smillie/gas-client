<?php

  require_once 'model/Persistable.php';
  require_once 'config.php';
  
/**
* Group model
*/
class Group implements Persistable
{
  
  public $name;
  public $gidnumber;
  public $members;
  
  static public function init($details) {
    $group = new Group;
    
    foreach ($details as $key => $value) {
      $group->$key = $value;
    }
    
    return $group;
    
  }
  
  static public function get($name){
    global $conf;
    
    $user = $_SESSION['username'];
    $password = $_SESSION['password'];
    $curl = new Curl;
    
    $url = $conf['api_protocol'] . "://$user:$password@".$conf['api_url'] ."/groups/$name";
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
    
    $url = $conf['api_protocol'] . "://$user:$password@".$conf['api_url'] ."/groups";
    $response = $curl->get($url, $vars = array());
    $result = json_decode($response, true);
    
    $allGroups = array();
    
    foreach ($result as $group) {
      $allGroups[] = self::init($group);
    }
    
    return $allGroups;
  }
  
  public function delete() {
    
  }
  
  public function save() {
    
  }
  
  public function addUser($username){
    
  }
  
  public function removeUser($username) {
    
  }
  
}
?>