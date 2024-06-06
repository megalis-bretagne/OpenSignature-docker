<?php

class SignCmsConfig {

  // init config
  function __construct() { 

      // some path
      $this->openssl = "/usr/bin/openssl";
      $this->logfile = "/tmp/cmssign.log";
     // ssl conf and CA path
     $this->capath = "/path/to/certificats";
     $this->rootca = $this->capath."/AC_signature.pem";
     $this->defcert = $this->capath."/signature.p12";
     $this->defpass = $this->capath."/signature.passwd";
     $this->pubcert = $this->capath."/signature.pem";
     $this->crlsign = $this->capath."/CRL_signature.pem";
     $this->legacyp12 = true;         // needed if openSSL > 3.0.0  (ubuntu22)
     
     // hardcoded decode script ,  only used for verification
     $topdir = dirname(dirname(__DIR__));
     $this->dispcms = $topdir."/app/script/displayCMS";


   }
    
}

