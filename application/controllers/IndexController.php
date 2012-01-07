<?php
class IndexController extends Zend_Controller_Action {

  // things to happen before the action has been run
  public function preDispatch() {
    /*
    A typical task to perform here is to check is the user is authorised to work with this controller.
    */
  }
  
  // things to happen after the action has been run
  public function postDispatch() {
    /*
    A typical task to perform here is to "touch" visitor's session - otherwise it'll be
    terminated after certain time passes from its creation, and usually it is needed to cut it
    after certain time passes since the last user's action.
    */
  }
  
  public function indexAction() {
    $config = Config::singleton();    
    $this->view->downloadForm = $this->_helper->FormsFactory($config->settings->forms->classes->download);
  }
  
  public function downloadAction() {
    $downloadForm = Form_Factory::getDownloadForm();
  }
}
?>