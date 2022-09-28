class WCTextColumn extends HTMLElement {
  constructor () {
    super();

    this.attachShadow({ mode: "open" });
    const template = document.createElement("template");
    template.innerHTML = `<style>.component {
  display: flex;
  flex-direction: column;
}</style><div class="component"></div>`;
    this.shadowRoot.appendChild(template.content.cloneNode(true));
    const root = this.shadowRoot.querySelector(".component");
    
    for (const child of this.children) {
      const text = document.createElement("wc-p");
      text.appendChild(child);
      root.appendChild(text);
    }
  }
}

customElements.define("wc-text-column", WCTextColumn);