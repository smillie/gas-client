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
          ));
    
  } 
  
  static public function updateDetails($app, $twig) {
    if (!isset($_SESSION['username'])) {
      $app->redirect('/');
    }
    
    $user = User::get($_SESSION['username']);
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

    $user->save();
    echo $twig->render('dashboard.html', 
        array(
          'currentuser'=>User::get($_SESSION['username']),
          'success'=>"Details successfully updated.",
          ));
    
    
  }
  
  static public function displayPasswordChange($app, $twig) {
    if (!isset($_SESSION['username'])) {
      $app->redirect('/');
    }
    
    echo $twig->render('changePassword.html', 
        array(
          'currentuser'=>User::get($_SESSION['username']),
          ));
    
  }
  
  static public function handlePasswordChange($app, $twig) {
    if (!isset($_SESSION['username'])) {
      $app->redirect('/');
    }
    
    if ($_POST['confirmpassword'] != $_POST['newpassword']) {
      echo $twig->render('changePassword.html', 
          array(
            'currentuser'=>User::get($_SESSION['username']),
            'error'=>'Passwords do not match.',
            ));
    } elseif (!User::authenticate($_SESSION['username'], $_POST['oldpassword'])) {
      echo $twig->render('changePassword.html', 
          array(
            'currentuser'=>User::get($_SESSION['username']),
            'error'=>'Password incorrect.',
            ));
    } elseif (!User::get($_SESSION['username'])->changePassword($_POST['newpassword'])) {
      echo $twig->render('changePassword.html', 
          array(
            'currentuser'=>User::get($_SESSION['username']),
            'error'=>'Error changing password.',
            ));
    } else {
      $_SESSION['password']=$_POST['newpassword'];
      echo $twig->render('changePassword.html', 
          array(
            'currentuser'=>User::get($_SESSION['username']),
            'success'=>'Password changed successfully.',
            ));
    }
    
    echo "wibble";
    // echo $twig->render('changePassword.html', 
    //     array(
    //       'currentuser'=>User::get($_SESSION['username']),
    //       'users'=>User::getAll(),
    //       'error'=>'Username or password is incorrect.',
    //       ));
    
  }
  
  static public function displayManageKeys($app, $twig) {
    if (!isset($_SESSION['username'])) {
      $app->redirect('/');
    }
    
    echo $twig->render('updateSSHKeys.html', 
        array(
          'currentuser'=>User::get($_SESSION['username']),
          'users'=>User::getAll(),
          ));
    
  }
}

?>