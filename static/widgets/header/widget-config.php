<?php return [
  "properties" => [
    "label" => "Header", // label for editor
    "class" => "WHeader", // name of exported class
    "category" => "Hidden", // editor category
  ],
  "imports" => [
    // widget->class, widget->class, ... (in order)
    "WTextEditor"
  ],
  "files" => [
    "source" => "header.js", // file of class
    "icon" => "", // file of icon for editor
    "styles" => "header.css" // styles scoped to element
  ]

  // available libraries: Component() -> HTML, widget-core
]; ?>