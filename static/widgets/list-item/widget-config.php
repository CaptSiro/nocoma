<?php return [
  "properties" => [
    "label" => "", // label for editor
    "class" => "WListItem", // name of exported class
    "category" => "Hidden", // editor category
  ],
  "imports" => [
    // widget->class, widget->class, ... (in order)
    "WTextEditor"
  ],
  "files" => [
    "source" => "list-item.js", // file of class
    "icon" => "", // file of icon for editor
    "styles" => "list-item.css" // styles scoped to element
  ]

  // available libraries: Component() -> HTML, widget-core
]; ?>