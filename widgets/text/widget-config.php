<?php return [
  "properties" => [
    "label" => "", // label for editor
    "class" => "WText", // name of exported class
    "category" => "hidden", // editor category
  ],
  "imports" => [
    // widget->class, widget->class, ... (in order)
  ],
  "files" => [
    "source" => "text.js", // file of class
    "icon" => "", // file of icon for editor
    "styles" => "text.css" // styles scoped to element
  ]

  // available libraries: html(HTMLDescription) -> HTML, widgetBuilder(WidgetDescription) -> HTML representation of widget
]; ?>