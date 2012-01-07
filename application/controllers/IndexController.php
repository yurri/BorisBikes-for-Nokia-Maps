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
  
  // default action to show the download form
  public function indexAction() {
    $config = Config::getConfig();
    // using controller helper to access the form which is stored in an XML file
    // form name is hardcoded, not very nice, but 'string value as parameter' approach is everywhere in Zend anyway
    // ther main reason although is that we anyway have it typed in XML file, so we can replace it here with a
    // constant, but not in XML. in brief, I think it's justified here, no need for overkill
    $this->view->downloadForm = $this->_helper->FormsFactory('download');
  }
  
  // serves the download request
  public function downloadAction() {
    $config = Config::getConfig();
    
    $request = $this->getRequest();
    if ($request->isPost()) {
    
      $downloadForm = $this->_helper->FormsFactory('download');
      if ($downloadForm->isValid($request->getPost())) {
        // parameters were submitted and are valid, now let's process them
        // during the process, exceptions might be thrown, but in this demo we handle them all in index.php
        $parser = new Parser($config->source->url);
        $stations = $parser->parseLocationsData($config->source->regexp);
        
        // $stations now holds all the recognised docking stations and their locations
        // now we need to send a file of proper type to user for dowloading
         
        // i'm feeling a bit uneasy about refering the elements this way, but it's very likey okay enough
        // please read my concerns and explanation in the indexAction function above
        $downloader = Downloader_Factory::getDownloader($downloadForm->getValue('filetype'));
        $downloader->download($stations);
        
        // now we need to exit because we don't need to ruin the file sent to user
        // downloaded didn't exit before because we might need to do some silent job here, e.g. logging etc.
        exit();
      }
    }
    
    // if we're here, then the download didn't happen for some reason and we have to return back to the index action
    $this->_redirect('index/index', array('exit' => true));
  }
}
?>