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
    global $conf;
    
    $user = $_SESSION['username'];
    $password = $_SESSION['password'];
    $curl = new Curl;
    
    $url = $conf['api_protocol'] . "://$user:$password@".$conf['api_url'] ."/groups/" . $this->name;
    $response = $curl->delete($url, $vars = array());
    
    if ($response->headers['Status'] != "200 OK") {
      return false;
    } else {
      return json_decode($response, true);
    }
  }
  
  public function save() {
    global $conf;
    
    if (self::get($this->name) == null) {
      //create new group (POST)    
      $details = get_object_vars($this);
      
      $user = $_SESSION['username'];
      $password = $_SESSION['password'];
      $curl = new Curl;

      $url = $conf['api_protocol'] . "://$user:$password@".$conf['api_url'] ."/groups";
      $response = $curl->post($url, json_encode($details));

      if ($response->headers['Status'] != "200 OK") {
        return false;
      } else {
        return true;
      }
    } else {
      //update existing group (PUT)
        $details = get_object_vars($this);
        
        $user = $_SESSION['username'];
        $password = $_SESSION['password'];
        $curl = new Curl;

        $url = $conf['api_protocol'] . "://$user:$password@".$conf['api_url'] ."/groups/" . $this->name;
        $response = $curl->put($url, json_encode($details));

        if ($response->headers['Status'] != "200 OK") {
          return false;
        } else {
          return true;
        }
      
    }
  }
  
  public function addUser($username){
    global $conf;
       
    $user = $_SESSION['username'];
    $password = $_SESSION['password'];
    $curl = new Curl;
    
    $body["user"] = $username;

    $url = $conf['api_protocol'] . "://$user:$password@".$conf['api_url'] ."/groups/".$this->name."/adduser";
    $response = $curl->post($url, json_encode($body));

    if ($response->headers['Status'] != "200 OK") {
      return false;
    } else {
      return true;
    }
  }
  
  public function removeUser($username) {
    global $conf;
       
    $user = $_SESSION['username'];
    $password = $_SESSION['password'];
    $curl = new Curl;
    
    $body["user"] = $username;

    $url = $conf['api_protocol'] . "://$user:$password@".$conf['api_url'] ."/groups/".$this->name."/deleteuser";
    $response = $curl->post($url, json_encode($body));

    if ($response->headers['Status'] != "200 OK") {
      return false;
    } else {
      return true;
    }
  }
  
}
?>