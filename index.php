<?php
// turning the error reporting on
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);

date_default_timezone_set('Europe/London');
define('APPLICATION_PATH', dirname(__FILE__) . '/application');
			  
set_include_path(
	'.' .                                           // current directory
  PATH_SEPARATOR . './library' .                  // path to Zend Framework
	PATH_SEPARATOR . APPLICATION_PATH . '/models' . // path to application's models directiry
	PATH_SEPARATOR . get_include_path()             // preserving the initial include path
);

try {
  // initialising Zend autoloader
  require_once 'Zend/Loader/Autoloader.php';
  $loader = Zend_Loader_Autoloader::getInstance();
  $loader->setFallbackAutoloader(true); // asking autoloader to act as a catch-all (any namespace)

  // it is easier and generally enough to have the config in a form of a global object
  // but for demonstration purposes here let's a more ideologically correct singleton model class
  $config = Config::singleton(); // underscore in class name makes Zend autoloader to search in Config/Singleton.php

  // starting session for the visitor
  // we don't really need it in this particular application as we don't distinguish between the users
  // but still this is a standard step
  // in some environments you need to call ini_set here to redefine session lifetime and storage folder
  // to avoid unwanted automatic sweeping
  Zend_Session::start();
  
  // now we need to start proccessing our request
  // the necessary thing for that is controller initialisation which we'll do below
  // however, in a more complicated application other actions are required (e.g. defining routes)
  // which I decided to skip to avoid code cluttering

  Zend_Layout::startMVC();  // this makes the action views output to be used with our general layout

  // before starting with controllers, let's tell where their helpers could be found
  Zend_Controller_Action_HelperBroker::addPath(
    APPLICATION_PATH . '/controllers/helpers'
  );

  // now the controllers
  $frontController = Zend_Controller_Front::getInstance();
  $frontController->throwExceptions(true);

  $frontController->setControllerDirectory(array(
    'default' => APPLICATION_PATH . '/controllers', // controllers directory
  ));

  // god help us!
	$frontController->dispatch();
  
} catch (Exception $e) {
  // as we only need the config file in this block (we refer admin email below), we could skip creating the singleton above
  // but generally the config should be always available, so the good style is to keep it where it is
	?>
	<h1>Unknown problem</h1>
	<p>
    <strong>Details:</strong> <?php echo $e->getMessage(); ?>
  <p>
    Please contact me at
		<a href="mailto:<?php echo $config->settings->admin->email; ?>"><?php echo $config->settings->admin->email; ?></a>
	</p>
	<?php
} 
?>