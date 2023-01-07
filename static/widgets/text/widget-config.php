<?php return [
  "properties" => [
    "label" => "Text", // label for editor
    "class" => "WText", // name of exported class
    "category" => "Base", // editor category
  ],
  "imports" => [
    // widget->class, widget->class, ... (in order)
  ],
  "files" => [
    "source" => "text.js", // file of class
    "icon" => "text.svg", // file of icon for editor
    "styles" => "text.css" // styles scoped to element
  ]

  // available libraries: Component() -> HTML, widgetBuilder(WidgetDescription) -> HTML representation of widget
];?>