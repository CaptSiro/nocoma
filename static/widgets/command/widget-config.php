<?php return [
  "properties" => [
    "label" => "Command", // label for editor
    "class" => "WCommand", // name of exported class
    "category" => "Hidden", // editor category
  ],
  "imports" => [
    // widget->class, widget->class, ... (in order)
  ],
  "files" => [
    "source" => "command.js", // file of class
    "icon" => "", // file of icon for editor
    "styles" => "command.css" // styles scoped to element
  ]

  // available libraries: html(HTMLDescription) -> HTML, widget-core
]; ?>