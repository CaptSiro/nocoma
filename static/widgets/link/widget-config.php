<?php return [
  "properties" => [
    "label" => "", // label for editor
    "class" => "WLink", // name of exported class
    "category" => "Hidden", // editor category
  ],
  "imports" => [
    // widget->class, widget->class, ... (in order)
  ],
  "files" => [
    "source" => "link.js", // file of class
    "icon" => "", // file of icon for editor
    "styles" => "link.css" // styles scoped to element
  ]

  // available libraries: html(HTMLDescription) -> HTML, widgetBuilder(WidgetDescription) -> HTML representation of widget
]; ?>