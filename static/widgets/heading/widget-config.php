<?php return [
  "properties" => [
    "label" => "Heading", // label for editor
    "class" => "WHeading", // name of exported class
    "category" => "Base", // editor category
  ],
  "imports" => [
    // widget->class, widget->class, ... (in order)
  ],
  "files" => [
    "source" => "heading.js", // file of class
    "icon" => "heading.svg", // file of icon for editor
    "styles" => "heading.css" // styles scoped to element
  ]

  // available libraries: Component() -> HTML, widgetBuilder(WidgetDescription) -> HTML representation of widget
];?>