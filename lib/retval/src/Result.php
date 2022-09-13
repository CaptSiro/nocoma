<?php

  class Result {
    private $succ, $failure;
    public function getSuccess () {
      return $this->succ;
    }
    public function getFailure () {
      return $this->failure;
    }

    public function __construct ($success, ?Exc $failure) {
      $this->succ = $success;
      $this->failure = $failure;
    }


    public function isSuccess (): bool {
      return isset($this->succ);
    }

    public function isFailure (): bool {
      return isset($this->failure);
    }



    public function succeeded (Closure $fn): Result {
      if ($this->isSuccess()) {
        return success($fn($this->succ));
      }

      return fail($this->failure);
    }

    public function failed (Closure $fn): Result {
      if ($this->isFailure()) {
        return fail($fn($this->failure));
      }

      return success($this->succ);
    }

    public function forwardFailure (Response $res) {
      if (isset($this->failure)) {
        $res->json($this->failure);
      }
    }

    public function either (Closure $successFN, Closure $failFN): Result {
      if ($this->isSuccess()) {
        return success($successFN($this->succ));
      }

      return fail($failFN($this->failure));
    }
  }



  function success ($value): Result {
    return new Result($value, null);
  }



  function fail (Exc $exception): Result {
    return new Result(null, $exception);
  }