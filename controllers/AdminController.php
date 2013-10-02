<?php

/**
* 
*/
class AdminController
{

  static public function addUser($app, $twig) {
    self::requireAdmin($app);
    
    echo $twig->render('admin/adduser.html', 
        array(
          'currentuser'=>User::get($_SESSION['username']),
          ));
  }

  static public function handleAddUser($app, $twig) {
    self::requireAdmin($app);
    
    if (User::get($_POST['username']) == NULL) {
    
      $user = new User;
      $user->displayname = $_POST['firstname']." ".$_POST['lastname'];
      $user->firstname = $_POST['firstname'];
      $user->lastname = $_POST['lastname'];
      $user->username = $_POST['username'];
      $user->studentnumber = $_POST['studentnumber'];
      $user->email = $_POST['email'];
    
      $user->save();
    
      $app->redirect('/admin/edit/'.$user->username);
    } else {
      echo $twig->render('admin/adduser.html', 
          array(
            'currentuser'=>User::get($_SESSION['username']),
            'error'=>'A user with the username '.$_POST['username'].' already exists.',
            ));
    }
  }

  static public function listUsers($app, $twig) {
    self::requireAdmin($app);
    
    echo $twig->render('admin/listusers.html', 
        array(
          'currentuser'=>User::get($_SESSION['username']),
          'users'=>User::getAll(),
          ));
  }
  
  static public function displaySearch($app, $twig) {
    self::requireAdmin($app);
    
    echo $twig->render('admin/search.html', 
        array(
          'currentuser'=>User::get($_SESSION['username']),
          ));
  }
  
  static public function handleSearch($app, $twig) {
    self::requireAdmin($app);
    
    echo $twig->render('admin/listusers.html', 
        array(
          'currentuser'=>User::get($_SESSION['username']),
          'users'=>User::search($_GET['query']),
          ));
  }
  
  static public function editUser($app, $twig, $username) {
    self::requireAdmin($app);
    
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
    self::requireAdmin($app);
    
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
  
  static public function displayNewUsers($app, $twig) {
    self::requireAdmin($app);
    
    echo $twig->render('admin/newusers.html', 
        array(
          'currentuser'=>User::get($_SESSION['username']),
          'users'=>NewMember::getAll(),
          ));
  }
  
  static public function handleNewUsers($app, $twig) {
    self::requireAdmin($app);

    $successmessage = "";
    $errormessage = "";
    
    if (isset($_POST['delete'])) {
      $user = NewMember::get($_POST['delete']);
      if ($user != null) {
        $user->delete();
        $successmessage = "$user->firstname $user->lastname has been removed from the approval queue.";
      } else {
          $errormessage = "Cannot delete - no such account exists.";
      }
    } elseif (isset($_POST['create'])) {
        $user = NewMember::get($_POST['create']);
        if ($user != NULL && $user->activate() != false) {
          $successmessage = "An account has been created for $user->firstname $user->lastname.";
        } else {
          $errormessage = "There was a problem creating the account - the username may already be taken.";
        }
      }
    
    
    echo $twig->render('admin/newusers.html', 
        array(
          'currentuser'=>User::get($_SESSION['username']),
          'success'=>$successmessage,
          'error'=>$errormessage,
          'users'=>NewMember::getAll(),
          ));
  }

  static public function listGroups($app, $twig) {
    self::requireAdmin($app);
    
    echo $twig->render('admin/listgroups.html', 
        array(
          'currentuser'=>User::get($_SESSION['username']),
          'groups'=>Group::getAll(),
          ));
  }
  
  static public function handleAddGroup($app, $twig) {
      self::requireAdmin($app);
      
      $successmessage = "";
      $errormessage = "";
      
      if (Group::get($_POST['newgroup']) == NULL) {
        $group = new Group;
        $group->name = $_POST['newgroup'];
        $group->save();
        $successmessage = "Group '".$group->name."' was successfully added.";
      } else {
        $errormessage = "Group '".$_POST['newgroup']."' already exists.";
      }

      echo $twig->render('admin/listgroups.html', 
          array(
            'currentuser'=>User::get($_SESSION['username']),
            'groups'=>Group::getAll(),
            'success'=>$successmessage,
            'error'=>$errormessage,
            ));
    
  }
  
  static public function editGroup($app, $twig, $name) {
      self::requireAdmin($app);

      echo $twig->render('admin/editgroup.html', 
          array(
            'currentuser'=>User::get($_SESSION['username']),
            'group'=>Group::get($name),
            ));
  }
  
  static public function handleEditGroup($app, $twig, $name) {
      self::requireAdmin($app);
      
      $group = Group::get($name);
      $successmessage = "";
      $errormessage = "";
  
      if (isset($_POST['delete'])) {
        $group->delete();
        $app->redirect("/admin/groups");
      }
      
      if (isset($_POST['addmember'])) {
        $group->addUser($_POST['newuser']);
        $successmessage = "User '".$_POST['newuser']."' has been added.";
      }
      
      if (isset($_POST['removemember'])) {
        $group->removeUser($_POST['removemember']);
        $successmessage = "User '".$_POST['removemember']."' has been removed.";
      }
      
      echo $twig->render('admin/editgroup.html', 
          array(
            'currentuser'=>User::get($_SESSION['username']),
            'group'=>Group::get($name),
            'success'=>$successmessage,
            'error'=>$errormessage,
            ));
  }

  static public function displayAuditLog($app, $twig) {
      self::requireAdmin($app);

      echo $twig->render('admin/listAuditLog.html', 
    array(
      'currentuser'=>User::get($_SESSION['username']),
      'log'=>AuditLog::getAll(),
      ));

  }
  
  static private function requireAdmin($app) {
    if (!isset($_SESSION['username'])) {
      $app->redirect('/');
    } else {
      $currentuser = User::get($_SESSION['username']);
      if (!$currentuser->isAdmin()) {
        $app->redirect('/');
      }
    }
  }

}
?>
