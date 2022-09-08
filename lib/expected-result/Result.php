<?php

  class Result {
    private $ok, $err;

    public function __construct ($ok, $error) {
      $this->ok = $ok;
      $this->err = $error;
    }


    public function isOk (): bool {
      return isset($this->ok);
    }

    public function isError (): bool {
      return isset($this->error);
    }



    public function good (Closure $fn): Result {
      if ($this->isOk()) {
        return ok($fn($this->ok));
      }

      return err($this->err);
    }

    public function bad (Closure $fn): Result {
      if ($this->isError()) {
        return err($fn($this->err));
      }

      return ok($this->ok);
    }

    public function either (Closure $okFN, Closure $errorFN): Result {
      if ($this->isOk()) {
        return ok($okFN($this->ok));
      }

      return err($errorFN($this->err));
    }
  }



  function ok ($value): Result {
    return new Result($value, null);
  }



  function err ($error): Result {
    return new Result(null, $error);
  }