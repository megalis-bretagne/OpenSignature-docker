<?php

/* *************************************************
 * this part is indended to be use by the sign_pades/sign_pades2 
 * shell script called by this php application
 * 
 * so let it , as is, in php comment !
 *
 * -- old engine ( PortableSigner ) , require : 
 *   CERT=/path/to/certificate/file.p12
 *   PASS=password for the previous p12 file
 * -- new 2023 engine ( open-pdf-sign ) , require : 
 *   CERT=/path/to/certificate/file.pem
 *   KEY=/path/to/key/without/passwd/file.pem
#CERT=/path/to/certificats/signature.p12
#PASS=password_for_p12
CERT=/path/to/certificats/signature.pem
KEY=/path/to/key/without/passwd/file.pem
 *
 * url to figure in the pdf signature comment
LOCATION="https://open-signature.some.where"
 *
 * relative path of default signature logo
RELDEFPIC="/pub/img/signatelec_h80.png"
 *
 **************************************************/

// this is the PHP part
//
class SignPadesConfig {

  // init config
  function __construct() { 

      // engine wrapper path
      $topdir = dirname(dirname(__DIR__));
      // old sign engine ( PortableSigner ), be sure to adjust php comment accordingly 
      //$this->signpades = $topdir."/app/signengine/sign_pades";
      // new 2023 sign engine ( open-pdf-sign ), be sure to adjust php comment accordingly 
      $this->signpades = $topdir."/app/signengine/sign_pades4";

      // log file
      $this->logfile = "/tmp/padesign.log";
      //$this->logfile = null;
      
  }
    
}

