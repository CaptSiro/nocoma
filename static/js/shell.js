Theme.get("/theme/website/" + webpage.src);

const viewportSizes = [{
  maxPixels: 1200,
  className: "viewport-tablet"
}, {
  maxPixels: 600,
  className: "viewport-smartphone"
}, {
  maxPixels: 425,
  className: "viewport-small-smartphone"
}];
new ResizeObserver(entries => {
  const viewport = entries[0];
  
  for (const size of viewportSizes) {
    if (size.maxPixels >= viewport.contentRect.width) {
      viewport.target.classList.add(size.className);
      continue;
    }
    
    viewport.target.classList.remove(size.className);
  }
}).observe($("#viewport"));



widgets.on("WRoot", async () => {
  const pageData = $("#page-data");
  const json = JSON.parse(pageData.textContent);
  
  const root = await WRoot.build(json);
  pageData.remove();
  
  window.rootWidget = root;
  document.body.appendChild(root.rootElement);
  document.widgetElement = root;
});