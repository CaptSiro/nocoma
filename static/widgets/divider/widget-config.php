<?php return [
  "properties" => [
    "label" => "Divider", // label for editor
    "class" => "WDivider", // name of exported class
    "category" => "Layout", // editor category
  ],
  "imports" => [
    // widget->class, widget->class, ... (in order)
  ],
  "files" => [
    "source" => "divider.js", // file of class
    "icon" => "divider.svg", // file of icon for editor
    "styles" => "divider.css" // styles scoped to element
  ]

  // available libraries: Component() -> HTML, widget-core
]; ?>