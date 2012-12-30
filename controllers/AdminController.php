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
  
  static public function displaySearch($app, $twig) {
    if (!isset($_SESSION['username'])) {
      $app->redirect('/');
    }
    
    echo $twig->render('admin/search.html', 
        array(
          'currentuser'=>User::get($_SESSION['username']),
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
  
  static public function editUser($app, $twig, $username) {
    if (!isset($_SESSION['username'])) {
      $app->redirect('/');
    }
    
    if (User::get($username) == null) {
      $app->redirect('/admin/listusers');
    }

     echo $twig->render('admin/editUser.html', 
       array(
         'currentuser'=>User::get($_SESSION['username']),
         'user'=>User::get($username),
         ));
     }
     
  static public function handleEditUser($app, $twig, $username) {
    
    if (!isset($_SESSION['username'])) {
      $app->redirect('/');
    }
    
    $user = User::get($username);
    $successmessage = "";
    
    if (isset($_POST['delete'])) {
      $user->delete();
      $successmessage = "User $username has been deleted.";
    }
    
    if (isset($_POST['resetpassword'])) {
      $temppassword = $user->resetPassword();
      $successmessage = "Password for $username reset to '$temppassword'.";
    }
    
    if (isset($_POST['update'])) {
      $user->displayname = $_POST["displayname"];
      $user->studentnumber = $_POST["studentnumber"];
      $user->email = $_POST["email"];
      switch ($_POST['loginshell']) {
        case "/bin/bash":
          $user->loginshell = "/bin/bash";
          break;
        case "/bin/tcsh":
          $user->loginshell = "/bin/tcsh";
          break;
        case "/bin/zsh":
          $user->loginshell = "/bin/zsh";
          break;
      }
      $user->expiry = $_POST["expiry"];
      switch ($_POST['hasPaid']) {
          case "yes":
              $user->paid = true;
              break;
          case "no":
              $user->paid = false;
              break;
      }
      $user->notes = $_POST["notes"];

      $user->save();
      $successmessage = "Details successfully updated.";
    }
    echo $twig->render('admin/editUser.html', 
        array(
          'currentuser'=>User::get($_SESSION['username']),
          'success'=>$successmessage,
          'user'=>User::get($username),
          )); 
  }

}
?>