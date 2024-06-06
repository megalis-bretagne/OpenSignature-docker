<?php
/**
 * Classe de DEbug pour envoyer le code SMS par mail à un compte unique.
 * Necessite la conf suivante pour fonctionner
 *
 *    $this->SmsClass = "Sms_Mail";
 *   $this->SmsParam = array();
 *
 *   $this->SmsParam['melsnd'] = $this->TopAppDir."/app/script/melsnd";
 *   $this->SmsParam['mailto'] = "<MAIL>";
 */
class Sms_Mail {
    
    public function __construct($param) {

        $this->operator = "Mail";
        $this->send_mail = $param['melsnd'];
        $this->mailTo = $param['mailto'];

    }

    function SendSMS( $phone, $message ) {

        $this->sendMail($phone, $message);
        // this always works !
        return array(
            'time' => date("Y/m/d-H:i:s"),
            'tel' => $phone,
            'operator' => $this->operator) ;
    }
 
    private function sendMail($phone, $message) {
        $msubj = base64_encode("[TEST] Votre code de signature mail");

        $tmpfname = tempnam("/tmp", "CodeMail");
        $fm = fopen($tmpfname, "a");
        fwrite($fm, $message."<br> Ce code est pour le numero ".$phone);
        fclose($fm);

        $launch= $this->send_mail." ".$this->mailTo." ".$msubj." ".$tmpfname;
        system(escapeshellcmd($launch));
        unlink($tmpfname);
    }
}

