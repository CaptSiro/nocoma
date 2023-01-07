<?php return [
  "properties" => [
    "label" => "Page", // label for editor
    "class" => "WPage", // name of exported class
    "category" => "Hidden", // editor category
  ],
  "imports" => [
    // widget->class, widget->class, ... (in order)
  ],
  "files" => [
    "source" => "page.js", // file of class
    "styles" => "page.css" // styles scoped to element
  ]

  // available libraries: Component() -> HTML, widget-core
]; ?>