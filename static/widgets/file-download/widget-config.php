<?php return [
  "properties" => [
    "label" => "File download", // label for editor
    "class" => "WFileDownload", // name of exported class
    "category" => "Base", // editor category
  ],
  "imports" => [
    // widget->class, widget->class, ... (in order)
  ],
  "files" => [
    "source" => "file-download.js", // file of class
    "icon" => "file-download.svg", // file of icon for editor
    "styles" => "file-download.css" // styles scoped to element
  ]

  // available libraries: Component() -> HTML, widget-core
]; ?>