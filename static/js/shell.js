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




const promise = WRoot.build(JSON.parse($("#page-data").textContent));
promise.then(rootWidget => {
  document.body.appendChild(rootWidget.rootElement);
  document.widgetElement = rootWidget;
});