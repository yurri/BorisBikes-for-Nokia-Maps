<?php
/*
A base class which puts a downloadable content into user's stream by providing special headers
*/
class Downloader {
  public function __construct() {
  }
  
  // MIME type associated with the format
  protected function getMIMEType() {
    return 'application/json';
  }
  
  // extension associated with this downloaded (to be redefined in descendant classes)
  protected function getExtension() {
    return 'json';
  }
  
  // builds a name for a downloaded file
  protected function getFilename() {
    $config = Config::getConfig();
    return $config->download->filename->prefix . date('Y-m-d') . '.' . $this->getExtension();
  }

  // this parent class just outputs JSON
  // for downloading in other formats its descendants should be used
  protected function downloadContent($stations) {
    echo json_encode($stations);    
  }
  
  // public function to start download
  public function download($stations) {
    // this is a "native" way to set headers in PHP
    // in Zend you're supposed to use another approach, e.g.
    // $this->getResponse()->setHeader('Content-Disposition', 'inline; filename=result.pdf');
    // however, for that you have to access the controller, and we're in the model
    // so I think it is okay to use the simple method below
    
    // these headers are only necessary for older versions of IE
    header('Accept-Ranges: none');
    header('Pragma: private');
    header('Cache-Control: private');
    // more common headers
    header('Content-type: ' . $this->getMIMEType());
    header('Content-disposition: attachment;filename=' . $this->getFilename());
    
    $this->downloadContent($stations);
    
    // we need to exit here to avoid runining the output but we rely on our caller to do that
    // because maybe it is needed to do something else silently after the download
  }
}
?>