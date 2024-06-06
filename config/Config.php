<?php

class Config {

  // init config
  function __construct() { 

    // ============================
    // CUSTOMIZABLE VARIABLES
    // ============================

    // Branding info
    //
    $this->brand = array();
    $this->brand['name'] = "OpenSignature";
    $this->brand['logo'] = "/pub/img/some_logo.png";
    $this->brand['favico'] = "/pub/img/some_ico.svg";

    // logo relative path and URL used in pdf signature slip  ( bordereau )
    // needed because we do not have $_SERVER variable during batch signature
    $this->brand['platformurl'] = "http://localhost:80/";
    $this->brand['platformlogo'] = "/pub/img/some_sign_logo.png";
    // do not forget to configure the same informations in SignPadesConfig.php
    // which are used for the signature inside the original pdf 
    // various messages ...
    $this->brand['vendor'] = "Dev Opensignature";
    $this->brand['desc1'] = "Plateforme de Signature";
    $this->brand['title'] = $this->brand['desc1']." ".$this->brand['vendor'];
    $this->brand['desc2'] = "un service de ".$this->brand['vendor'];
    $this->brand['vendorurl'] = "https://www.some.where";
    $this->brand['contacturl'] = "https://www.some.where/contact";
    $this->brand['rgpdmail'] = "rgpd@some.where";

   
    // application topdir & topurl
    //     TopAppDir should match the PATH where you installed this software
    //               or let automaticaly guess based on __DIR__ predefined constant
    //     TopAppUrl should be empty on a virtual host,
    //               use the subdirectory of the site otherwise
    $this->TopAppDir = dirname(dirname(__DIR__));
    $this->TopAppUrl = "";

    // redis : self explanatory
    $this->redis_server = "redis";
    $this->redis_port = 6379;
    //$this->redis_socket = "/path/to/redis.sock";
    $this->redis_base = 1;     // choose a free redis base number here

    // should we use JQuery File Upload ? 
    // better to say yes (1), unless you use very old browser !
    $this->JQFileUpload = 1;

    //  sisgnspool directory
    // used by batch script for signature
    // DO NOT forget to either initialise :
    //   - incrontab for this directory (see app/script/initincrontab)
    //   - unspoolsign service (see app/script/unspoolsign_service_init)
    $this->signspool = $this->TopAppDir."/tmp/spoolsign";
    // incron/inotify verif  ( incron | inotify ), must match the choosen solution above
    $this->inVerif = "incron";

    // mail sending configuration located in /config/MailConfig.sh file 
    
    // Auto Create Account ( only for SSO  based accounts )
    $this->AutoCreateAccount = 1;

    // autoindex file
    $this->AutoIndex = "autoindex.html";

    // default folder duration before automatic destruction  ( in seconds )
    $this->folderDuration = 15811200;     // 6 months

    // display of DAV (direct access) menu ?
    $this->DavMenu = false;

    // remove original pdf after signature
    $this->RemoveOriginalPdf = false;

    // signature engine (by file extension)
    $this->SignEngine = Array(
        "pdf" => "PADES",
        "DEFAULT" => "CMS"
    );
    
    // sms class and param
    //$this->SmsClass = "Sms_OVH";
    $this->SmsClass = "Sms_Mail";
    $this->SmsParam = array();
    // DUMB sms param
    $this->SmsParam['dumb'] = "fakeSMS";
    $this->SmsParam['melsnd'] = $this->TopAppDir."/app/script/melsnd";
    $this->SmsParam['mailto'] = "mail@mail.fr";


    // OVH account
    //$this->SmsParam['posturl'] = "/sms/sms-YYYYYYY-1/jobs";              // POST url
    //$this->SmsParam['appkey'] = "XXXXXXXXXXXXXXXXXXX";                      // Application Key
    //$this->SmsParam['secret'] = "YYYYYYYYYYYYYYYYYYYYYYYYYYYY";      // Application Secret
    //$this->SmsParam['endpoint'] = "ovh-eu";                              // Endpoint of API OVH Europe
    //$this->SmsParam['consumerkey'] = "ZZZZZZZZZZZZZZZZZZZZZZZZZZZZZ"; // Consumer Key

    // SSO 
    $this->sso = "none";         // values = none | openidc
    //$this->ssourl = "https://sso.some.where/realms/XXXXX";
    //$this->ssoid = "ClientIDHere";
    //$this->ssosecret = "ClientSecretHere";
    // sso scope and fields ( required scope may be empty , fields retreived by the SSO system )
    //$this->sso_scope = "";
    //$this->sso_field_userid = "";            // user unique identifier : login@domain.xx
    //$this->sso_field_name = "";              // user last name
    //$this->sso_field_gvname = "";            // user given name
     // sso admin url,  not displayed if empty
     $this->ssoadmin = "";

  
    // LOOL & WOPI
    $this->loolurl = "";
    $this->wopitpu = "";

    // GAAPSE API for user signature info
    $this->gapiurl = "";
    $this->gapikey = "";
    
    // internal TSA CA, ( only used for old signature proof, generated without AC )
    $this->internalTsaCA = "/path/to/timestamp/certif/timestamp_ac.pem";
    
    // ============================
    // LESS CUSTOMIZABLE VARIABLES
    // ============================

    // inner subdirectories
    // modify only if you have changed the software layout
    $this->DataDir = "/data";
    $this->AbsDataDir = $this->TopAppDir.$this->DataDir;
    $this->CacheDir = $this->TopAppDir."/tmp/cache";

    // Signature Proof Storage
    $this->ProofDir = $this->TopAppDir."/proof";

    // URL generation method
    // simple URLs, pretiest, but require apache mod-rewrite  ( see .htaccess )
    $this->csxDocClass = "/doc";
    $this->csxMgmtClass = "/mgt";
    $this->csxAdmClass = "/adm";
    // long URLs, use it if apache mod-rewrite not available for your system
    // $this->csxDocClass = "/wbx.php/Doc";
    // $this->csxMgmtClass = "/wbx.php/Mgmt";
    // $this->csxAdmClass = "/wbx.php/Admin";

  }
    
}

