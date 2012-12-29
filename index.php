<?php

  session_start();
  session_regenerate_id();

  require 'vendor/autoload.php';
  require_once 'model/User.php';
  require_once 'model/Group.php';
  require_once 'model/NewMember.php';
  require_once 'controllers/DashboardController.php';
  require_once 'controllers/LoginController.php';
  
  $app = new \Slim\Slim();
  
  $loader = new Twig_Loader_Filesystem('views');
  $twig = new Twig_Environment($loader, array(
      // 'cache' => '/cache',
  ));
  $twig->addFilter('md5', new Twig_Filter_Function('md5'));

  $app->get('/', function() use ($app, $twig) {
    LoginController::display($app, $twig);
  });
  
  $app->post('/auth/login', function() use ($app, $twig) {
    LoginController::login($app, $twig);
  });
  
  $app->get('/auth/logout', function() use ($app, $twig) {
    LoginController::logout($app);
  });
  
  $app->get('/register', function() use ($app, $twig) {
    LoginController::displayRegister($twig);
  });
  
  $app->post('/register', function() use ($app, $twig) {
    LoginController::displayPostRegister($twig);
  });
  
  $app->get('/dashboard', function() use ($app, $twig) {
    DashboardController::displayDetails($app, $twig);
  });
  
  $app->get('/changepassword', function() use ($app, $twig) {
    DashboardController::displayPasswordChange($app, $twig);
  });
  
  $app->post('/changepassword', function() use ($app, $twig) {
    DashboardController::handlePasswordChange($app, $twig);
  });
  
  $app->get('/managekeys', function() use ($app, $twig) {
    DashboardController::displayManageKeys($app, $twig);
  });
  
  $app->run();
  

  // echo $twig->render('admin/listusers.html', 
  //     array(
  //       'currentuser'=>User::get('asmillie'),
  //       'users'=>User::getAll(),
  //       'error'=>'Username or password is incorrect.',
  //       ));
  // var_dump( NewMember::get("18"));
  
?>