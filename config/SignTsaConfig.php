<?php

class SignTsaConfig {

  // init config
  function __construct() { 

      // some path
      $this->openssl = "/usr/bin/openssl";
      $this->logfile = "/tmp/tsasign.log";
      //$this->logfile = null;

      // =====================================
      // internal TimeStamper TSA key+certificats,  not officially recognized, work nevertheless....

      $this->internalTSA = array();
      $this->internalTSA['type'] = "internal";
      
      $tspath = "/certificats/freetsa";
      $this->internalTSA['tscrt'] = $tspath."/timestamp_crt.pem";
      $this->internalTSA['tskey'] = $tspath."/timestamp_key.pem";
      $this->internalTSA['rootca'] = $tspath."/timestamp_ac.pem";
      $this->internalTSA['sslcnf'] = $tspath."/timestamp_ssl.cnf";
      
      // ==========================================================
      // if you do not want to use your own Timestamp certificats ,
      // you may use some tested, freely available timestamper web-service below...
      // ( see https://gist.github.com/Manouchehri/fd754e402d98430243455713efada710 )
      //
      
      // =====================================
      // freetsa.org  TimeStamp web service,  

      $this->freeTSA = array();
      $this->freeTSA['type'] = "remote";

      $this->freeTSA['url'] = "https://freetsa.org/tsr";
      $this->freeTSA['rootca'] = $tspath."/cacert.pem";

      // =====================================
      // timestamp.globalsign.com  TimeStamp web service,  

      $this->globalsign1TSA = array();
      $this->globalsign1TSA['type'] = "remote";

      $this->globalsign1TSA['url'] = "http://timestamp.globalsign.com/tsa/r6advanced1";
      $this->globalsign1TSA['rootca'] = "/path/to/globalsign/ca.pem";

      // =====================================
      // rfc3161timestamp.globalsign.com  TimeStamp web service,  

      $this->globalsign2TSA = array();
      $this->globalsign2TSA['type'] = "remote";

      $this->globalsign2TSA['url'] = "http://rfc3161timestamp.globalsign.com/advanced";
      $this->globalsign2TSA['rootca'] = "/path/to/globalsign/ca.pem";

      //
      // =====================================
      // used timestamper solution  ( choose one defined above ...)
      //$this->default = $this->internalTSA;
      $this->default = $this->freeTSA;
      //$this->default = $this->globalsign1TSA;
      //$this->default = $this->globalsign2TSA;

      
      // signature engine git version   not really related to TSA, but not worth a config file on its own !
      $topdir = dirname(dirname(__DIR__));
      $this->gitversion = $topdir."/app/config/GitVersion.txt";

   }
    
}

