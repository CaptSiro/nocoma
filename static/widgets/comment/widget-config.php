<?php return [
  "properties" => [
    "label" => "Comment", // label for editor
    "class" => "WComment", // name of exported class
    "category" => "Hidden", // editor category
  ],
  "imports" => [
    // widget->class, widget->class, ... (in order)
  ],
  "files" => [
    "source" => "comment.js", // file of class
    "icon" => "", // file of icon for editor
    "styles" => "comment.css" // styles scoped to element
  ]

  // available libraries: Component() -> HTML, widget-core
]; ?>