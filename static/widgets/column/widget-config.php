<?php return [
  "properties" => [
    "label" => "Column", // label for editor
    "class" => "WColumn", // name of exported class
    "category" => "Containers", // editor category
  ],
  "imports" => [
    // widget->class, widget->class, ... (in order)
  ],
  "files" => [
    "source" => "column.js", // file of class
    "icon" => "column.svg", // file of icon for editor
    "styles" => "column.css" // styles scoped to element
  ]

  // available libraries: Component() -> HTML, widgetBuilder(WidgetDescription) -> HTML representation of widget
];?>