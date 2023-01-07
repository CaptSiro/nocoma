<?php return [
  "properties" => [
    "label" => "Image", // label for editor
    "class" => "WImage", // name of exported class
    "category" => "Base", // editor category
  ],
  "imports" => [
    // widget->class, widget->class, ... (in order)
  ],
  "files" => [
    "source" => "img.js", // file of class
    "icon" => "image.svg", // file of icon for editor
    "styles" => "img.css" // styles scoped to element
  ]

  // available libraries: Component() -> HTML, widgetBuilder(WidgetDescription) -> HTML representation of widget
];?>