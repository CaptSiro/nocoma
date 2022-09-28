class WCTable extends HTMLElement {
  constructor () {
    super();

    this.attachShadow({ mode: "open" });
    const root = document.createElement("div");
    this.shadowRoot.appendChild(root);
    
    for (const child of this.children) {
      const col = document.createElement("wc-text-column");
      col.appendChild(child);
      root.appendChild(col);
    }
    
  }
}

console.log("Table class imported!");
console.log("Hot reload")

customElements.define("wc-table", WCTable);