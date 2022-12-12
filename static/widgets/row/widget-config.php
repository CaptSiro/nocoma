<?php return [
  "properties" => [
    "label" => "Row", // label for editor
    "class" => "WRow", // name of exported class
    "category" => "Containers", // editor category
  ],
  "imports" => [
    // widget->class, widget->class, ... (in order)
  ],
  "files" => [
    "source" => "row.js", // file of class
    "icon" => "row.svg", // file of icon for editor
    "styles" => "row.css" // styles scoped to element
  ]

  // available libraries: html(HTMLDescription) -> HTML, widgetBuilder(WidgetDescription) -> HTML representation of widget
];?>