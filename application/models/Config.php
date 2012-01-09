<?php
/*
Singleton class providing the application configuration.
By inheriting from a singleton template, we may no longer bother about keeping a single instance
and focus on business logic instead.
*/
class Config extends Abstract_Singleton {
  private $__settings = null;  // a field to hold a Zend_Config_Ini instance
  
  // as construction is protected, no accidental "new" outside is possible
  protected function __construct() {
    // loading the configuration file
    $this->__settings = new Zend_Config_Ini(
      APPLICATION_PATH . '/config/config.ini',
      APPLICATION_ENV
    );
  }
  
  public static function getConfig() {
    // PHP allows to refer a private field here without a getter function which I find odd
    return self::singleton()->__settings;
  }
}
?>