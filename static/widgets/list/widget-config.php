<?php return [
  "properties" => [
    "label" => "List", // label for editor
    "class" => "WList", // name of exported class
    "category" => "Layout", // editor category
  ],
  "imports" => [
    // widget->class, widget->class, ... (in order)
    "WListItem"
  ],
  "files" => [
    "source" => "list.js", // file of class
    "icon" => "list.svg", // file of icon for editor
    "styles" => "list.css" // styles scoped to element
  ]

  // available libraries: Component() -> HTML, widget-core
]; ?>