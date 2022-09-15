<?php

  require_once __DIR__ . "/Mail.php";

  class MailTemplate {
    public const PASSWORD_RECOVERY_GATEWAY = "http://localhost/nocoma/templates/password-recovery.php";

    static function passwordRecovery ($user, $urlArg): MailTemplate {
      return new MailTemplate($user, "Password Recovery", "
        Hello,<br><br>
        
        We are reaching to you in case of password recovery. If you are interested please continue here: <a href=\"" . self::PASSWORD_RECOVERY_GATEWAY . "?prid=$urlArg\" target=\"_blank\">Reset Password.</a><br><br>
        
        If you are not interested, just ignore or delete this email.

        Do not forward this email to anyone. It may put your account in danger.
      ");
    }

    static function verifyAccount ($user, $code): MailTemplate {
      return new MailTemplate($user, "Verify Account", "
        Hello,<br><br>

        Your code for verification is: <span style=\"font-family: monospace; font-size: 20px;\">$code</span>.<br><br>

        After you verify with given code, your registration will be completed.
      ");
    }



    public $subject, $content, $user;
    public function __construct ($user, $subject, $content) {
      $this->user = $user;
      $this->subject = $subject;
      $this->content = $content;
    }

    public function send () {
      Mail::message($this->user->email, $this->subject, $this->content);
    }
  }