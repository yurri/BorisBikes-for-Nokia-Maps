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
    after certain time passes since the last user's action
    */
  }
  
  // the default action to happen
  public function indexAction() {
    $this->view->test = 'Test';
  }
}
?>