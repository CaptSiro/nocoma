class SequantialAnimation {
  /** @type {boolean} */
  #abort = false;
  abort () {
    this.#abort = true;

    return new Promise((resolve => {
      this.#finishEvent.addListener((() => {
        this.#abort = false;
        resolve();
      }).bind(this), true);
    }).bind(this))
  }

  /** 
   * @typedef SequantialAnimationReturn
   * @property {boolean} isFinished
   * @property {number=} timeout
   */
  /**
   * @callback SequantialAnimationStep
   * @param {number=} iteration
   * @param {SequantialAnimation=} animation
   * @returns {boolean|SequantialAnimationReturn} return `true` to end animation
   */

  /** @type {SequantialAnimationStep|Promise} */
  step;

  state = {};

  /** @type {number} */
  iteration = 0;

  /** @type {number} */
  requestId;

  /** @type {number} */
  deltaTime = 0;

  /** @type {number} */
  #lastSnapshot;

  /** @type {FireAble<SequantialAnimation>} */
  #finishEvent;

  /**
   * @param {Listener|Listener[]} listener 
   */
  onFinished (listener) {
    this.#finishEvent.addListener(listener);
  }

  /**
   * @param {SequantialAnimationStep} step 
   * @param {FireAble<SequantialAnimation>|undefined} finishEvent 
   */
  constructor (step, finishEvent) {
    this.step = step;
    if (finishEvent === undefined) {
      this.#finishEvent = new FireAble();
    } else {
      this.#finishEvent = finishEvent;
    }
  }

  /**
   * @returns {boolean|SequantialAnimationReturn}
   */
  next () {
    return this.step(this.iteration++, this);
  }

  /**
   * @returns {Promise<boolean>}
   */
  async nextAsync () {
    return await this.step(this.iteration++, this);
  }



  /** @type {boolean} */
  #run;

  play () {
    this.#run = false;
    this.#abort = false;
    this.state = {};
    if (this.step.constructor.name !== "AsyncFunction") {
      this.#lastSnapshot = Date.now();
      this.requestId = requestAnimationFrame(this.playRAF.bind(this));
      return;
    }
    
    return new Promise((async resolve => {
      while (this.#run !== true) {
        if (this.#abort === true) {
          break;
        }
        this.#run = await this.nextAsync();
      }
  
      this.#finishEvent.fire(this);
      resolve();
    }).bind(this));
  }

  /**
   * playRequestAnimationFrame
   */
  playRAF () {
    const current = Date.now();
    this.deltaTime = (current - this.#lastSnapshot);
    this.#lastSnapshot = current;

    const resp = this.next();

    if (resp === true || resp.isFinished === true || this.#abort === true) {
      cancelAnimationFrame(this.requestId);
      this.#finishEvent.fire(this);
    } else {
      if (resp.timeout !== undefined) {
        new Promise(resolve => setTimeout(resolve, resp.timeout))
          .then((() => {
            // this.#lastSnapshot = Date.now();
            this.requestId = requestAnimationFrame(this.playRAF.bind(this));
          }).bind(this));
      } else {
        this.requestId = requestAnimationFrame(this.playRAF.bind(this));
      }
    }
  }
}