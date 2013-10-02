<?php

  session_start();
  session_regenerate_id();

  require 'vendor/autoload.php';
  require_once 'model/User.php';
  require_once 'model/Group.php';
  require_once 'model/NewMember.php';
  require_once 'model/AuditLog.php';
  require_once 'controllers/DashboardController.php';
  require_once 'controllers/LoginController.php';
  require_once 'controllers/AdminController.php';
  require_once 'controllers/ElectionsController.php';
  
  $app = new \Slim\Slim();
  
  $loader = new Twig_Loader_Filesystem('views');
  $twig = new Twig_Environment($loader, array(
      'cache' => 'views/cache',
  ));
  $twig->addFilter('md5', new Twig_Filter_Function('md5'));
  $twig->addGlobal('config', $conf);

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
    LoginController::handleRegister($twig);
  });
  
  $app->get('/dashboard', function() use ($app, $twig) {
    DashboardController::displayDetails($app, $twig);
  });
  $app->post('/dashboard', function() use ($app, $twig) {
    DashboardController::updateDetails($app, $twig);
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
  $app->post('/managekeys', function() use ($app, $twig) {
    DashboardController::handleManageKeys($app, $twig);
  });
  
  
  //Admin routes
  //TODO CSV export?
  $app->get('/admin/search', function() use ($app, $twig) {
    AdminController::handleSearch($app, $twig);
  });
  $app->get('/admin/listusers', function() use ($app, $twig) {
    AdminController::listUsers($app, $twig);
  });
  $app->get('/admin/edit', function() use ($app, $twig) {
    AdminController::displaySearch($app, $twig);
  });
  $app->get('/admin/edit/:username', function($username) use ($app, $twig) {
    AdminController::editUser($app, $twig, $username);
  });
  $app->post('/admin/edit/:username', function($username) use ($app, $twig) {
    AdminController::handleEditUser($app, $twig, $username);
  });
  $app->get('/admin/adduser', function() use ($app, $twig) {
    AdminController::addUser($app, $twig);
  });
  $app->post('/admin/adduser', function() use ($app, $twig) {
    AdminController::handleAddUser($app, $twig);
  });
  
  $app->get('/admin/newusers', function() use ($app, $twig) {
    AdminController::displayNewUsers($app, $twig);
  });
  $app->post('/admin/newusers', function() use ($app, $twig) {
    AdminController::handleNewUsers($app, $twig);
  });
  
  $app->get('/admin/groups', function() use ($app, $twig) {
    AdminController::listGroups($app, $twig);
  });
  $app->post('/admin/groups', function() use ($app, $twig) {
    AdminController::handleAddGroup($app, $twig);
  });
  $app->get('/admin/groups/:name', function($name) use ($app, $twig) {
    AdminController::editGroup($app, $twig, $name);
  });
  $app->post('/admin/groups/:name', function($name) use ($app, $twig) {
    AdminController::handleEditGroup($app, $twig, $name);
  });
  
  
  $app->get('/elections/nominate/', function() use ($app, $twig) {
    ElectionsController::nominateForm($app, $twig);
  });
  $app->post('/elections/nominate/', function() use ($app, $twig) {
    ElectionsController::handleNomination($app, $twig);
  });

  $app->get('/admin/audit', function() use ($app, $twig) {
    AdminController::displayAuditLog($app, $twig);
  }); 
  
  $app->run();
  
?>
