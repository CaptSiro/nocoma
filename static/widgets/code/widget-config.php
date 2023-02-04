<?php return [
  "properties" => [
    "label" => "Code Block", // label for editor
    "class" => "WCode", // name of exported class
    "category" => "Text", // editor category
  ],
  "imports" => [
    // widget->class, widget->class, ... (in order)
    "WTextEditor"
  ],
  "files" => [
    "source" => "code.js", // file of class
    "icon" => "code.svg", // file of icon for editor
    "styles" => "code.css" // styles scoped to element
  ]

  // available libraries: Component() -> HTML, widget-core
]; ?>