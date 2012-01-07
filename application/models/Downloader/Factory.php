<?php
class Downloader_Factory {
  // defining the supported file types
  const FILE_TYPE_LDB = 'ldb';
  const FILE_TYPE_CSV = 'csv';
  
  // returns an instance of the downloader which corresponds to the file type requested by user
  public static function getDownloader($fileType) {
    // again, no need to initialise, but that's my habit
    $downloaded = null;
    
    switch ($fileType) {
    // download Nokia Maps file
    case self::FILE_TYPE_LDB:
      $downloader = new Downloader_LDB();
      break;
      
    // download CSV file
    case self::FILE_TYPE_CSV:
      $downloader = new Downloader_CSV();
      break;
      
    // type not recognised, using the base class which does little transformation to the data
    default:
      $downloader = new Downloader();
      // no need for break here, but again I'm used to it
      break;
    }
    
    return $downloader;
  }
}
?>