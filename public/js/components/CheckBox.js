{
  const template = document.createElement("template");
  template.innerHTML = `
    <style>
      .check-box {
        display: flex;
        align-items: center;
        width: 100%;
        height: max-content;
        padding: 4px;
      }

      .check-box .center-abs {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(1);
      }

      .check-box .mount {
        min-width: 1em;
        min-height: 1em;
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: inherit;
      }
      
      .check-box .mount .state-changer {
        width: 100%;
        height: 100%;
        border: 1px solid black;
        border-radius: inherit;

        transition: background 250ms linear;
      }

      .check-box .mount .state-changer:hover {
        cursor: pointer;
      }

      .check-box.checked .mount .state-changer {
        background: #058DF3 !important;
        
        transition: background 250ms linear;
      }

      .check-box .mount .pulse {
        width: 100%;
        height: 100%;
        border-radius: inherit;
        background-color: #c3c3c3;
        opacity: 0;
        padding: 1px;
      }

      .check-box.checked .mount .pulse {
        opacity: 1;
        background-color: transparent;
        transform: translate(-50%, -50%) scale(2);

        transition: background-color 500ms linear, transform 500ms ease-out;
      }

      .check-box p {
        margin: 0px;
        margin-left: 0.5em;
      }
    </style>

    <div class="check-box">
      <div class="mount">
        <div class="pulse center-abs"></div>
        <div class="state-changer center-abs"></div>
      </div>
      <p>
        <slot />
      </p>
    </div>
  `;



  class HTMLCheckBoxElement extends HTMLElement {
    /** @type {HTMLDivElement} */
    #root;
    /** @type {HTMLDivElement} */
    #stateChanger;
    /** @type {boolean} */
    #isChecked = false;
    /** @type {MutationObserver} */
    #observer = new MutationObserver((mutations => {
      for (let i = 0; i < mutations.length; i++) {
        if (mutations[i].type === "attributes") {
          this.#attributeChange();
        }
      }
    }).bind(this));

    constructor () {
      super();
      this.attachShadow({ mode: "open" });
      this.shadowRoot.appendChild(template.content.cloneNode(true));

      this.#root = this.shadowRoot.querySelector(".check-box");
      this.#stateChanger = this.shadowRoot.querySelector(".state-changer");

      console.log(this.#root);
      console.log(this.#stateChanger);
    }

    isChecked () {
      return this.#isChecked;
    }

    toggle () {
      this.#isChecked = !this.#isChecked;

      if (this.#isChecked === true) {
        this.setAttribute("checked", "true");
        this.#root.classList.add("checked");
      } else {
        this.setAttribute("checked", "false");
        this.#root.classList.remove("checked");
      }
    }

    #attributeChange () {
      this.#root.style.borderRadius = this.#getAttr("cb-border-radius", "unset");
      this.#stateChanger.style.background = this.#getAttr("cb-background", "unset");
      this.#stateChanger.style.border = this.#getAttr("cb-border", "unset");
    }

    /**
     * @template T
     * @param {string} name 
     * @param {T} def 
     * @returns {string|T}
     */
    #getAttr (name, def) {
      return this.getAttribute(name) ?? def;
    }

    connectedCallback () {
      this.#observer.observe(this, { attributes: true });
      this.setAttribute("checked", "false");
      this.#stateChanger.addEventListener("pointerdown", this.toggle.bind(this));
    }

    disconnectedCallback () {
      this.#stateChanger.removeEventListener("pointerdown", this.toggle.bind(this));
      this.#observer.disconnect(this);
    }
  }


  window.customElements.define("check-box", HTMLCheckBoxElement);
}