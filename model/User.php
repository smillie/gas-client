<?php

  require_once 'model/Persistable.php';
  require_once 'config.php';
  
/**
* User model
*/
class User implements Persistable
{
  
  public $username;
  public $firstname;
  public $lastname;
  public $displayname;
  public $email;
  public $title;
  public $studentnumber;
  public $status;
  public $expiry;
  public $paid;
  public $notes;
  public $loginshell;
  public $homedirectory;
  public $uidnumber;
  public $gidnumber;
  public $sshkeys;
  public $groups;
  public $isAdmin;
  
  static public function init($details) {
    $user = new User;
    
    foreach ($details as $key => $value) {
      $user->$key = $value;
    }
    
    return $user;
    
  }
  
  static public function authenticate($username, $password) {
    global $conf;
    
    $request["username"] = $username;
    $request["password"] = $password;
    
    $curl = new Curl;
    
    $url = $conf['api_protocol'] . "://".$conf['api_url'] ."/authenticate";
    $response = $curl->post($url, json_encode($request));
    $result = json_decode($response, true);
    
    if ($username == "" || $password == "" || $response->headers['Status'] != "200 OK") {
      return false;
    } else {
      return true;
    }
  }
  
  static public function get($username) {
    global $conf;
    
    $user = $_SESSION['username'];
    $password = $_SESSION['password'];
    $curl = new Curl;
    
    $url = $conf['api_protocol'] . "://$user:$password@".$conf['api_url'] ."/users/$username";
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
    
    $url = $conf['api_protocol'] . "://$user:$password@".$conf['api_url'] ."/users";
    $response = $curl->get($url, $vars = array());
    $result = json_decode($response, true);
    
    $allUsers = array();
    
    foreach ($result as $user) {
      $allUsers[] = self::init($user);
    }
    
    return $allUsers;
  }
  
  static public function search($query) {
    global $conf;
    
    $user = $_SESSION['username'];
    $password = $_SESSION['password'];
    $curl = new Curl;
    
    $url = $conf['api_protocol'] . "://$user:$password@".$conf['api_url'] ."/search/$query";
    $response = $curl->get($url, $vars = array());
    $result = json_decode($response, true);
    
    $queryResult = array();
    
  
    if ($response->headers['Status'] == "200 OK") {
      foreach ($result as $user) {
        $queryResult[] = self::init($user);
      }
    }
    
    return $queryResult;
  }
  
  public function delete() {
    global $conf;
    
    $user = $_SESSION['username'];
    $password = $_SESSION['password'];
    $curl = new Curl;
    
    $url = $conf['api_protocol'] . "://$user:$password@".$conf['api_url'] ."/users/" . $this->username;
    $response = $curl->delete($url, $vars = array());
    
    if ($response->headers['Status'] != "200 OK") {
      return false;
    } else {
      return json_decode($response, true);
    }
  }
  
  public function resetPassword() {
    global $conf;
    
    $user = $_SESSION['username'];
    $password = $_SESSION['password'];
    $curl = new Curl;
    
    $url = $conf['api_protocol'] . "://$user:$password@".$conf['api_url'] ."/users/" . $this->username . "/resetpassword";
    $response = $curl->post($url, $vars = array());
    
    if ($response->headers['Status'] != "200 OK") {
      return false;
    } else {
      $temp = json_decode($response, true);
      return $temp['password'];
    }
  }
  
  public function changePassword($password) {
    global $conf;
    
    $newpassword["password"]=$password;
    
    $user = $_SESSION['username'];
    $password = $_SESSION['password'];
    $curl = new Curl;
    
    $url = $conf['api_protocol'] . "://$user:$password@".$conf['api_url'] ."/users/" . $this->username . "/changepassword";
    $response = $curl->post($url, json_encode($newpassword));
    
    if ($response->headers['Status'] != "200 OK") {
      return false;
    } else {
      return true;
    }
  }
  
  public function save() {
    global $conf;
    
    if (self::get($this->username) == null) {
      //create new user (POST)    
      $details = get_object_vars($this);
      
      $user = $_SESSION['username'];
      $password = $_SESSION['password'];
      $curl = new Curl;

      $url = $conf['api_protocol'] . "://$user:$password@".$conf['api_url'] ."/users";
      $response = $curl->post($url, json_encode($details));

      if ($response->headers['Status'] != "200 OK") {
        return false;
      } else {
        return true;
      }
    } else {
      //update existing user (PUT)
        $details = get_object_vars($this);
        
        $user = $_SESSION['username'];
        $password = $_SESSION['password'];
        $curl = new Curl;

        $url = $conf['api_protocol'] . "://$user:$password@".$conf['api_url'] ."/users/" . $this->username;
        $response = $curl->put($url, json_encode($details));

        if ($response->headers['Status'] != "200 OK") {
          return false;
        } else {
          return true;
        }
      
    }
  }
  
  public function isAdmin() {
    return $this->isAdmin;
  }

  
}

?>
