class InfiniteScroller {
  /** @type {HTMLElement} */
  container;
  
  /** @type {(index: number)=>Promise<HTMLElement|undefined>} */
  loader;
  
  #loaderIndex = 0;
  
  #observer;
  
  /**
   * @param {HTMLElement} container
   * @param {(index: number)=>Promise<HTMLElement|undefined>} loader
   */
  constructor(container, loader) {
    this.container = container;
    this.loader = loader;
    
    this.#observer = new IntersectionObserver(entries => {
      const lastElement = entries[0];
      
      if (!lastElement.isIntersecting) {
        return;
      }
      
      this.#observer.unobserve(lastElement.target);
      this.callLoader();
    }, {
      root: container,
      rootMargin: "50px",
      threshold: 0
    });
    
    this.reset();
  }
  
  
  callLoader () {
    this.loader(this.#loaderIndex).then(last => {
      if (last !== undefined) {
        this.#observer.observe(last);
      }
    });
    
    this.#loaderIndex++;
  }
  
  
  reset() {
    this.container.textContent = "";
    this.#loaderIndex = 0;
    this.#observer.disconnect();
    
    this.callLoader();
  }
}