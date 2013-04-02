<?php

/**
* 
*/
class ElectionsController
{

  static public function nominateForm($app, $twig) {      

      global $conf;
      $curl = new Curl;
          
      $url = $conf['api_protocol'] . "://".$conf['api_url'] ."/elections/positions";
      $response = $curl->get($url, $vars = array());
      $offices = json_decode($response, true);

     
    echo $twig->render('elections/nominations.html', 
        array(
          'currentuser'=>User::get($_SESSION['username']),
          'offices' => $offices,
          )
      );
  }

  
}
?>