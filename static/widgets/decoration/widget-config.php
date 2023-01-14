<?php return [
  "properties" => [
    "label" => "Text Decoration", // label for editor
    "class" => "WTextDecoration", // name of exported class
    "category" => "Hidden", // editor category
  ],
  "imports" => [
    // widget->class, widget->class, ... (in order)
  ],
  "files" => [
    "source" => "decoration.js", // file of class
    "icon" => "", // file of icon for editor
    "styles" => "decoration.css" // styles scoped to element
  ]

  // available libraries: Component() -> HTML, widget-core
]; ?>