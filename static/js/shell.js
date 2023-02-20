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




const viewportListeners = [];
/**
 * @type {ViewportDimensions}
 */
let viewportDimensions;
window.addEventListener("resize", () => {
  const width = window.innerWidth;
  const height = window.innerHeight;
  
  viewportDimensions = {
    width,
    height,
    convertedHeight: height,
    convertedWidth: width,
    maxHeight: height,
    maxWidth: width
  }
  
  for (const viewportListener of viewportListeners) {
    viewportListener(viewportDimensions)
  }
});
/**
 * @param {ViewportListener} callback
 */
function onViewportResize (callback) {
  viewportListeners.push(callback);
}

function viewportResize () {
  window.dispatchEvent(new Event("resize"));
}


widgets.on("WRoot", async () => {
  const pageData = $("#page-data");
  const json = JSON.parse(pageData.textContent);
  
  const root = await WRoot.build(json);
  pageData.remove();
  
  window.rootWidget = root;
  document.body.appendChild(root.rootElement);
  document.widgetElement = root;
});