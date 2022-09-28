class WCParagraph extends HTMLElement {
  constructor () {
    super();
    
    const template = document.createElement("template");
    template.innerHTML = `{%template%}`;

    const style = document.createElement("style");
    const html = document.createElement("html");

    style.innerHTML = `{%css%}`;

    html.innerHTML = `{%html%}`;

    this.attachShadow({ mode: "open" });
    this.shadowRoot.appendChild(template.content.cloneNode(true));
  }
}


console.log("Paragraph class imported!");


customElements.define("wc-p", WCParagraph);