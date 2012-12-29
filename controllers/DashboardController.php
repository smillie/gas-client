<?php

/**
* 
*/
class DashboardController
{
  static public function displayDetails($app, $twig) {
    if (!isset($_SESSION['username'])) {
      $app->redirect('/');
    }
    
    echo $twig->render('dashboard.html', 
        array(
          'currentuser'=>User::get($_SESSION['username']),
          'users'=>User::getAll(),
          'error'=>'Username or password is incorrect.',
          ));
    
  } 
}

?>