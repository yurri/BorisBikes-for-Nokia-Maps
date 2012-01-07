<?php
/*
Singleton class providing the application configuration.
By inheriting from a singleton template, we may no longer bother about keeping a single instance
and focus on business logic instead.
*/
class Config extends Abstract_Singleton {
  private $__settings = null;  // a field to hold a Zend_Config_Ini instance
  
  public function __construct() {
    // loading the configuration file
    $this->__settings = new Zend_Config_Ini(
      // hardcoded value clearly isn't the best style but using it here is justified
      // it can be improved though by defining constants in index.php and then
      // but I don't think that's necessary
      './application/config/config.ini',
      // change the following line in a diffent environment to read a different config section
      'production'
    );
  }
  
  public static function getConfig() {
    // PHP allows to refer a private field here without a getter function which I find odd
    return self::singleton()->__settings;
  }
}
?>