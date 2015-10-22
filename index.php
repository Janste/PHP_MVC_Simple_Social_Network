<?php

require_once("controller/MainController.php");
require_once('view/GeneralView.php');
require_once('model/FacadeModel.php');

// We turn on PHP output buffering feature
ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Start session
session_start();

// Set up model
$m = new \model\FacadeModel();

// Set up view
$v = new \view\GeneralView();

// Run the controller
$controller = new \controller\MainController($m, $v);
$controller->run();

// Show output
$v->render($m->isUserLoggedIn($v->getUserClient()));

