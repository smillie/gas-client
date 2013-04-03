<?php

/**
* 
*/
class ElectionsController
{

  static public function nominateForm($app, $twig) {      

      global $conf;
      $user = $_SESSION['username'];
      $password = $_SESSION['password'];
      $curl = new Curl;
      
      if ($conf['elections'] != TRUE) {
          $app->redirect('/dashboard');
      }
          
      $url = $conf['api_protocol'] . "://".$conf['api_url'] ."/elections/positions";
      $response = $curl->get($url, $vars = array());
      $offices = json_decode($response, true);
      
      $url = $conf['api_protocol'] . "://$user:$password@".$conf['api_url'] ."/elections/eligibleMembers";
      $response = $curl->get($url, $vars = array());
      $eligibleMembers = json_decode($response, true);

     
    echo $twig->render('elections/nominations.html', 
        array(
          'currentuser'=>User::get($_SESSION['username']),
          'offices' => $offices,
          'eligibleMembers' => $eligibleMembers,
          )
      );
  }
  
  static public function handleNomination($app, $twig) {      

      global $conf;
      $user = $_SESSION['username'];
      $password = $_SESSION['password'];
      $curl = new Curl;
      $currentuser = User::get($_SESSION['username']);
      
      $data['nominations'] = $_POST;
      $data['user'] = "$currentuser->firstname $currentuser->lastname";
          
      $url = $conf['api_protocol'] . "://$user:$password@".$conf['api_url'] ."/elections/nominate";
      $response = $curl->post($url, json_encode($data));
      $result = json_decode($response->body, false);      
      
      $url = $conf['api_protocol'] . "://".$conf['api_url'] ."/elections/positions";
      $response = $curl->get($url, $vars = array());
      $offices = json_decode($response, true);
      
      $url = $conf['api_protocol'] . "://$user:$password@".$conf['api_url'] ."/elections/eligibleMembers";
      $response = $curl->get($url, $vars = array());
      $eligibleMembers = json_decode($response, true);
      
      
    echo $twig->render('elections/nominations.html', 
        array(
          'currentuser'=>User::get($_SESSION['username']),
          'success' => "Thank you for your nominations.",
          'offices' => $offices,
          'eligibleMembers' => $eligibleMembers,
          )
      );
  }

  
}
?>