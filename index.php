<?php
/*
One of the powers (as well as of disadvantages when used improperly) of Zend framework
is that the same thing can be performed in many ways depending on the level of detalisation and complexity required.

The usual way to start a Zend application is using the bootstrap class and INI declarations, but here we'll
cut the corners and demonstrate how we can avoid that in a lightweight application.

As this app doesn't need modules, plugins etc. we can start the the dispatching process manually like it's shown below.
This will also give us an understanding of the order in which things happen and what's exactly happening behind the scenes.
*/

// turning the error reporting on
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);

date_default_timezone_set('Europe/London');

// these varialbes can be also defined in .htaccess file
defined('APPLICATION_PATH') || define('APPLICATION_PATH', dirname(__FILE__) . '/application');
defined('APPLICATION_ENV') || define('APPLICATION_ENV', 'production');
			  
set_include_path(implode(PATH_SEPARATOR, array(
  'library',                    // path to Zend Framework
  APPLICATION_PATH . '/models', // path to application's models directory for Zend Autoloader to serve it as well
  get_include_path()            // retaining initial value
)));

try {
  // initialising Zend autoloader
  require_once 'Zend/Loader/Autoloader.php';
  $loader = Zend_Loader_Autoloader::getInstance();
  $loader->setFallbackAutoloader(true); // asking autoloader to act as a catch-all for any namespace

  // here we don't have a bootstrap to request the INI file reference from, so let's use this chance
  // to demonstrate a custom singleton model and its usage
  $config = Config::getConfig();
  
  // now we need to start proccessing our request
  // the necessary thing for that is controller initialisation which we'll perform below
  // in a more complicated application other actions are required (e.g. dealing with rules and modules)
  // which we are skipping as explained above

  Zend_Layout::startMVC();  // this makes the action views output to be used with our general layout

  // before starting with controllers, let's tell where their helpers could be found
  Zend_Controller_Action_HelperBroker::addPath(
    APPLICATION_PATH . '/controllers/helpers'
  );

  // now the controllers...
  $frontController = Zend_Controller_Front::getInstance();
  $frontController->throwExceptions(true);

  $frontController->setControllerDirectory(array(
    'default' => APPLICATION_PATH . '/controllers', // controllers directory
  ));

  // god help us!
	$frontController->dispatch();
  
} catch (Exception $e) {
  // as we only really need the config file in this block (we refer admin email below), we could have skipped creating the singleton above
  // but generally the config should be always available, so the good style is to keep it where it is
	?>
	<h1>Houston, we've had a problem</h1>
  <p>
    Please contact me at
		<a href="mailto:<?php echo $config->admin->email ? $config->admin->email : 'akopov@hotmail.co.uk'; ?>"><?php echo $config->admin->email ? $config->admin->email : 'akopov@hotmail.co.uk'; ?>"></a>.
    Thanks.
	</p>
  <p>
    <strong>Details</strong>
  </p>
  <p>
    <?php echo nl2br($e->__toString()); ?>
  </p>
	<?php
} 
?>