class WCParagraph extends HTMLElement {
  constructor () {
    super();
    
    const template = document.createElement("template");
    template.innerHTML = `<style>.component {
  padding: 8px;
  font: inherit;
  border: 1px solid black;
}</style><p class="component">
  <slot />
</p>`;

    const style = document.createElement("style");
    const html = document.createElement("html");

    style.innerHTML = `.component {
  padding: 8px;
  font: inherit;
  border: 1px solid black;
}`;

    html.innerHTML = `<p class="component">
  <slot />
</p>`;

    this.attachShadow({ mode: "open" });
    this.shadowRoot.appendChild(template.content.cloneNode(true));
  }
}


console.log("Paragraph class imported!");


customElements.define("wc-p", WCParagraph);