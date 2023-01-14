/** @template T */
class FireAble {
  /**
   * @callback Listener
   * @param {T} fireValue
   * @returns {void}
   */

  /**
   * @typedef ListenerNode
   * @property {boolean} deleteAfterCalled
   * @property {Listener} callback
   */
  
  /** @type {ListenerNode[]} */
  #nodes = [];
  
  /**
   * @param {Listener=} defaultListener 
   * @param {boolean=} deleteAfterCalled
   */
  constructor (defaultListener = undefined, deleteAfterCalled = undefined) {
    if (defaultListener !== undefined) {
      this.addListener(defaultListener, deleteAfterCalled);
    }
  }

  /** 
   * @param {Listener|Listener[]} listener 
   * @param {boolean=} deleteAfterCalled
   */
  addListener (listener, deleteAfterCalled = undefined) {
    if (listener instanceof Array) {
      this.#nodes = this.#nodes.concat(
        listener.map(
          callback => ({
            callback,
            deleteAfterCalled: !!deleteAfterCalled // conversion to boolean -> undefined: false
          })
        )
      );
      return;
    }
    
    this.#nodes.push({
      callback: listener,
      deleteAfterCalled: !!deleteAfterCalled
    });
  }

  /**
   * @param {T} value
   */
  fire (value) {
    const toDelete = [];
    for (let i = 0; i < this.#nodes.length; i++) {
      this.#nodes[i].callback(value);

      if (this.#nodes[i].deleteAfterCalled === true) {
        toDelete.push(i);
      }
    }

    this.#nodes = this.#nodes.filter((n, i) => !toDelete.includes(i));
  }
}