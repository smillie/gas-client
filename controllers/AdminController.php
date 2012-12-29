<?php

/**
* 
*/
class AdminController
{

  static public function listUsers($app, $twig) {
    if (!isset($_SESSION['username'])) {
      $app->redirect('/');
    }
    
    echo $twig->render('admin/listusers.html', 
        array(
          'currentuser'=>User::get($_SESSION['username']),
          'users'=>User::getAll(),
          ));
  }
  
  static public function handleSearch($app, $twig) {
    if (!isset($_SESSION['username'])) {
      $app->redirect('/');
    }
    
    echo $twig->render('admin/listusers.html', 
        array(
          'currentuser'=>User::get($_SESSION['username']),
          'users'=>User::search($_GET['query']),
          ));
  }

}
?>