<?php return [
  "properties" => [
    "label" => "Link", // label for editor
    "class" => "WLink", // name of exported class
    "category" => "Text", // editor category
  ],
  "imports" => [
    // widget->class, widget->class, ... (in order)
//    "WText"
  ],
  "files" => [
    "source" => "link.js", // file of class
    "icon" => "link.svg", // file of icon for editor
    "styles" => "link.css" // styles scoped to element
  ]

  // available libraries: Component() -> HTML, widgetBuilder(WidgetDescription) -> HTML representation of widget
];?>