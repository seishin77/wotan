<?php
require_once 'conf/mail.conf';
require_once 'PHPMailer/PHPMailerAutoload.php';

class mailer{
  private static $_init   = false;
  private static $_mailer = null;

  private function __construct(){}

  public static function init(){
    if(self::$_init === false){
      switch(SMTPTYPE){
      case 'SMTP':
        self::$_mailer = new PHPMailer;
        self::$_mailer->isSMTP();                // Set mailer to use SMTP
        self::$_mailer->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
        self::$_mailer->SMTPAuth = true;         // Enable SMTP authentication
        self::$_mailer->Username = EMAIL;        // SMTP username
        self::$_mailer->Password = EMAILPASS;    // SMTP password
        self::$_mailer->SMTPSecure = 'tls';      // Enable TLS encryption, `ssl` also accepted
        self::$_mailer->Port = 587;              // TCP port to connect to
        self::$_mailer->WordWrap = 80;           // set word wrap
        self::$_mailer->IsHTML(true);            // send as HTML
        self::$_mailer->SMTPKeepAlive = true;    // SMTP connection will not close after each email sent, reduces SMTP overhead
        self::$_mailer->setFrom(EMAIL, EMAILNAME);
        self::$_mailer->addReplyTo(EMAIL, EMAILNAME);
        break;
      default:
        break;
      }
    }

    self::$_init   = true;
  }

  public static function mail($to, $subject = '(No subject)', $message = ''){
    if(self::$_init === false)
      self::init();

    $from_user = "=?UTF-8?B?" . base64_encode(EMAILNAME) . "?=";
    $subject   = "=?UTF-8?B?" . base64_encode($subject)   . "?=";

    switch(SMTPTYPE){
      case 'SMTP':
        self::$_mailer->clearAddresses();
        self::$_mailer->addAddress($to);
        self::$_mailer->msgHTML($message);
        self::$_mailer->Subject = $subject;
        return self::$_mailer->send();
      case 'PHP':
        $headers = sprintf("From: %s <%s>\r\n", $from_user, EMAIL).
                 'MIME-Version: 1.0' . "\r\n" .
                 'Content-type: text/html; charset=UTF-8' . "\r\n";
        return mail($to, $subject, $message, $headers);
    }
  }
}
