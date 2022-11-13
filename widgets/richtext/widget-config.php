<?php return [
  "properties" => [
    "label" => "", // label for editor
    "class" => "WRichText", // name of exported class
    "category" => "", // editor category
  ],
  "imports" => [
    // widget->class, widget->class, ... (in order)
  ],
  "files" => [
    "source" => "richtext.js", // file of class
    "icon" => "", // file of icon for editor
    "styles" => "" // styles scoped to element
  ]

  // available libraries: html(HTMLDescription) -> HTML, widgetBuilder(WidgetDescription) -> HTML representation of widget
]; ?>