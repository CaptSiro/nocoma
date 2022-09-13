class Timer {
  /** @type {number} */
  #seconds;
  /** @type {number} */
  #current = 0;
  /** @type {boolean} */
  #finished = false;
  /**
   * @returns {boolean}
   */
  isFinished () {
    return this.#finished;
  }
  /** @type {number} */
  #timerID;
  /**
   * @callback TimerChange
   * @param {number} timestamp
   * @returns {void}
   */
  /** @type {TimerChange} */
  #callback;
  /**
   * @param {TimerChange} cb 
   */
  onChange (cb) {
    this.#callback = cb;
  }
  
  /** @type {()=>void} */
  #onFinish = () => {};
  onFinish (cb) {
    this.#onFinish = cb;
  }

  constructor (seconds) {
    this.#seconds = seconds;
    this.#current = seconds;
    this.#finished = true;
  }

  start () {
    if (this.#finished === true) {
      this.#current = this.#seconds;
      this.#finished = false;
    }

    this.#timerID = setInterval((() => {
      this.#callback(--this.#current);
      if (this.#current == 0) {
        this.stop();
      }
    }).bind(this), 1000);
  }

  stop () {
    clearInterval(this.#timerID);
    this.#finished = true;
    this.#onFinish();
  }
  
  halt () {
    clearInterval(this.#timerID);
  }

  reset () {
    this.stop();
    this.start();
  }
}