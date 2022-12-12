</div>
  
  <script>
    const promise = WRoot.build(JSON.parse(document.currentScript.previousElementSibling.textContent));
    promise.then(rootWidget => {
      document.body.appendChild(rootWidget.rootElement);
      document.widgetElement = rootWidget;
    });
  </script>
</body>
</html>