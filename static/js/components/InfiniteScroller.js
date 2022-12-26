class InfiniteScroller {
  /** @type {HTMLElement} */
  container;
  
  /** @type {(index: number)=>Promise<HTMLElement|undefined>} */
  loader;
  
  /** @type {(rootContainer: HTMLElement)=>void} */
  #rootContainerReSetter;
  
  #loaderIndex = 0;
  
  #observer;
  
  /**
   * @param {HTMLElement} rootContainer
   * @param {(index: number)=>Promise<HTMLElement|undefined>} loader
   * @param {(rootContainer: HTMLElement)=>void} rootContainerReSetter
   */
  constructor(rootContainer, loader, rootContainerReSetter = undefined) {
    this.container = rootContainer;
    this.loader = loader;
    this.#rootContainerReSetter = rootContainerReSetter ?? (rootContainer => {
      rootContainer.textContent = "";
    });
    
    this.#observer = new IntersectionObserver(entries => {
      const lastElement = entries[0];
      
      if (!lastElement.isIntersecting) {
        return;
      }
      
      this.#observer.unobserve(lastElement.target);
      this.callLoader();
    }, {
      root: rootContainer,
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
    this.#rootContainerReSetter(this.container);
    this.#loaderIndex = 0;
    this.#observer.disconnect();
    
    this.callLoader();
  }
}