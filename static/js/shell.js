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
}).observe(document.body);



widgets.on("WRoot", async () => {
  const pageData = $("#page-data");
  const json = JSON.parse(pageData.textContent);
  json.webpage = webpage;
  
  const root = await WRoot.build(json);
  pageData.remove();
  
  window.page = root;
  document.body.appendChild(root.rootElement);
  document.widgetElement = root;
});