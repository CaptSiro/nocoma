<?php return [
  "properties" => [
    "label" => "Quote", // label for editor
    "class" => "WQuote", // name of exported class
    "category" => "Text", // editor category
  ],
  "imports" => [
    // widget->class, widget->class, ... (in order)
    "WTextEditor"
  ],
  "files" => [
    "source" => "quote.js", // file of class
    "icon" => "quote.svg", // file of icon for editor
    "styles" => "quote.css" // styles scoped to element
  ]

  // available libraries: Component() -> HTML, widget-core
]; ?>