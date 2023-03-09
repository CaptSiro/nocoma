<?php

  require_once __DIR__ . "/Mail.php";

  class MailTemplate {
    static function passwordRecovery ($user, $url): MailTemplate {
      return new MailTemplate($user, "Password Recovery", "
        Hello,<br><br>
        
        We are reaching to you in case of password recovery. If you are interested please continue here: <a href=\"$url\" target=\"_blank\">Reset Password.</a><br><br>
        
        If you are not interested, just ignore or delete this email.

        Do NOT share this email to anyone. It may put your account in danger.
      ");
    }
    
    static function postTakedown (User $user, $postTitle, $reason): MailTemplate {
      return new MailTemplate($user, "Your post has been taken down", "
        Administrator has taken down your post '$postTitle' because of:<br><br>
        
        $reason<br><br>
        
        After fixing your post, you can try to appeal for removal of take down in your dashboard.
      ");
    }
    
    static function appealDeclined (User $user, $postTitle): MailTemplate {
      return new MailTemplate($user, "Your appeal has been declined", "
        Administrator has reviewed your appeal for removal of take down for post '$postTitle' and chose to decline it.<br><br>
        
        Please read the instructions from email you got in last email before applying for new appeal.
      ");
    }
    
    static function appealAccepted (User $user, $postTitle): MailTemplate {
      return new MailTemplate($user, "Your appeal has been accepted", "
        Administrator has reviewed your appeal for removal of take down for post '$postTitle' and chose to accept it.<br><br>
        
        For the next posts, try to make them follow the guidelines of Nocoma.
      ");
    }

    static function verifyAccount ($user, $code): MailTemplate {
      return new MailTemplate($user, "Verify Account", "
        Hello,<br><br>

        Your code for verification is: <span style=\"font-family: monospace; font-size: 20px;\">$code</span>.<br><br>

        After you verify with given code, your registration will be completed.
      ");
    }

    static function userBanned ($user): MailTemplate {
      return new MailTemplate($user, "Account restriction", "
        Your account has been restricted, which means that you can no longer publish new posts and all posts you have created are not accessible. All your comments have been taken down and you can not comment under posts of others.<br><br>
        
        There is still possibility for this restriction to be removed, but that is not very likely.
      ");
    }
    
    static function userUnbanned ($user): MailTemplate {
      return new MailTemplate($user, "Removed account restriction", "
        Your account is no longer restricted. You can publish posts and add comments again.
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