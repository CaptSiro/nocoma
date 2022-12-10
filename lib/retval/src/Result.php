<?php
  //todo finish Result::all()

  class ResultSet {
    private $success, $failures;
    public function getSuccess () {
      return $this->success;
    }
    public function getFailures () {
      return $this->failures;
    }

    public function __construct (?array $success, ?array $failures) {
      $this->success = empty($success) ? null : $success;
      $this->failures = empty($failures) ? null : $failures;
    }


    public function isSuccess (): bool {
      return isset($this->succ);
    }

    public function isFailure (): bool {
      return isset($this->failure);
    }

    public function forwardFailure (Response $res) {
      if (isset($this->failure)) {
        $res->json($this->failure);
      }
    }

    public function strip (Closure $failFN) {
      if ($this->isFailure()) {
        return $failFN($this->failures);
      }

      return $this->succ;
    }
  }
  
  /**
   * @template T
   */
  class Result {
    /**
     * @var T $succ
     */
    private $succ, $failure;
  
    /**
     * @return T
     */
    public function getSuccess () {
      return $this->succ;
    }
    public function getFailure () {
      return $this->failure;
    }
  
    /**
     * @param T|null $success
     * @param Exc|null $failure
     */
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
  
  
    /**
     * @param Closure $function (T $success)->Result
     * @return Result
     */
    public function succeeded (Closure $function): Result {
      if ($this->isSuccess()) {
        return success($function($this->succ));
      }

      return fail($this->failure);
    }
  
    /**
     * @param Closure $function (Exc $exception)->Result
     * @return Result
     */
    public function failed (Closure $function): Result {
      if ($this->isFailure()) {
        return fail($function($this->failure));
      }

      return success($this->succ);
    }

    public function forwardFailure (Response $response) {
      if (isset($this->failure)) {
        $response->json($this->failure);
      }
    }
  
    /**
     * @param Closure $successFunction (T $success)->Result
     * @param Closure $failFunction (Exc $exception)->Result
     * @return Result
     */
    public function either (Closure $successFunction, Closure $failFunction): Result {
      if ($this->isSuccess()) {
        return success($successFunction($this->succ));
      }

      return fail($failFunction($this->failure));
    }
  
    /**
     * @param Closure $failFunction
     * @return T|mixed|null
     */
    public function strip (Closure $failFunction) {
      if ($this->isFailure()) {
        return $failFunction();
      }

      return $this->succ;
    }
  
    /**
     * @param Result ...$results
     * @return ResultSet
     */
    public static function all (Result ...$results): ResultSet {
      if (empty($results)) return new ResultSet(null, [new NullPointerExc("Working with 0 results. You must pass at least one.")]);

      $failed = [];
      $succeeded = [];

      foreach ($results as $result) {
        if ($result->isFailure()) {
          $failed[] = $result->getFailure();
        } else {
          $succeeded[] = $result->getSuccess();
        }
      }

      return new ResultSet($succeeded, $failed);
    }
  }
  
  
  /**
   * @template R
   * @param R $value
   * @return Result<R>
   */
  function success ($value): Result {
    return new Result($value, null);
  }
  
  
  /**
   * @param Exc $exception
   * @return Result<null>
   */
  function fail (Exc $exception): Result {
    return new Result(null, $exception);
  }