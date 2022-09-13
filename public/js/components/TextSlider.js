class TextSlider {
  /** @type {HTMLElement} */
  #element;
  /** @type {HTMLSpanElement} */
  #spanElement;
  /** @type {number} */
  #speed;
  /** @type {number} */
  #gapSize;
  /** @type {string} */
  #content = "";
  /** @type {SequantialAnimation} */
  #animation;
  





  /**
   * 
   * @param {HTMLElement} element 
   * @param {*} param1 
   */
  constructor (element, {
    speed = 50,
    onHover = true,
    gapSize = 200,
    delay = 500
  } = {}) {

    // Object setup
    this.#element = element;
    this.#speed = speed;
    this.#gapSize = gapSize;
    
    
    // HTML setup
    this.#element.classList.add("-text-slide");
    this.#content = this.#element.innerHTML;
    this.#element.textContent = "";
    this.#spanElement = this.#createSpan(this.#content, "-text-slide-content");
    this.#element.append(this.#spanElement);
    


    if (onHover === true) {
      this.#element.addEventListener("pointerenter", (evt => {
        this.play();
      }).bind(this));

      this.#element.addEventListener("pointerleave", (evt => {
        this.reset();
      }).bind(this));
      
      window.addEventListener("pointerleave", (evt => {
        this.reset();
      }).bind(this));
    }
    


    this.#animation = new SequantialAnimation(this.#step.bind(this));
  }
  




  
  play () {
    this.#element.classList.add("animation");
    this.#animation.play();
  }
  




  
  reset () {
    this.#element.classList.remove("animation");
    this.#animation.abort()
      .then((() => {
        this.#element.scroll({
          left: 0,
          top: 0,
          behavior: "smooth"
        });
        
        this.#element.textContent = "";
        this.#element.append(this.#spanElement);
      }).bind(this));
  }
  




  
  /**
   * @param {string} content 
   * @param  {...string} classes 
   * @returns {HTMLSpanElement}
   */
  #createSpan (content, ...classes) {
    const s = document.createElement("span");
    s.classList.add(...classes);
    s.innerHTML = content;

    return s;
  }
  




  
  /** @type {SequantialAnimationStep} */
  #step (i, animation) {
    const containerWidth = this.#element.clientWidth;
    const spanWidth = this.#spanElement.scrollWidth;

    if (spanWidth <= containerWidth) {
      return true;
    }


    let scrolled = animation.state.scrolled ?? 0;
    scrolled += ((animation.deltaTime / 1000) * this.#speed);

    if ((scrolled + containerWidth > (spanWidth - 50)) && animation.state.hasNotBeenSpwned !== true) {
      // spwn gap and new span
      const additionalSpan = this.#spanElement.cloneNode(true);
      const spanGap = document.createElement("div");
      spanGap.style.minWidth = this.#gapSize + "px";

      this.#element.append(spanGap, additionalSpan);
      animation.state.hasNotBeenSpwned = true;
    }
    
    if (scrolled > (spanWidth + this.#gapSize)) {
      // delete appended span and gap
      scrolled -= spanWidth + this.#gapSize;
      this.#element.textContent = "";
      this.#element.append(this.#spanElement);
      animation.state.hasNotBeenSpwned = false;
    }

    this.#element.scroll(scrolled, 0);
    animation.state.scrolled = scrolled;
    return false;
  }
}


(() => {
  const style = document.createElement("style");
  style.id = "text-slider-styles";
  style.textContent = `
    .-text-slide {
      overflow: hidden;
      text-overflow: ellipsis;
      display: flex;
    }
    .-text-slide.animation {
      text-overflow: unset;
    }
    span.-text-slide-content {
      white-space: nowrap;
    }
  `;

  document.head.appendChild(style);
})();