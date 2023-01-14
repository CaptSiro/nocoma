<?php return [
  "properties" => [
    "label" => "Text Editor", // label for editor
    "class" => "WTextEditor", // name of exported class
    "category" => "Hidden", // editor category
  ],
  "imports" => [
    // widget->class, widget->class, ... (in order)
    "WTextDecoration",
  ],
  "files" => [
    "source" => "text-editor.js", // file of class
    "icon" => "", // file of icon for editor
    "styles" => "text-editor.css" // styles scoped to element
  ]

  // available libraries: Component() -> HTML, widget-core
]; ?>