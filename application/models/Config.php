<?php
// Singleton providing application config.
// By inheriting from a singleton template, we may not bother about keeping a single instance
// and focus on business logic.
class Config extends Abstract_Singleton {
  public $settings = null;  // Zend Config Ini is loaded here

  public function __construct() {
    // loading the configuration file
    $this->settings = new Zend_Config_Ini(
      // hardcoded value clearly isn't the best style but using it here is justified
      // it can be improved though by defining constants in index.php and then
      // using them here, but that'd clutter the demo
      './application/config/config.ini',
      // change the following line in a diffent environment to read a different config section
      'production'
    );
  }
}
?>