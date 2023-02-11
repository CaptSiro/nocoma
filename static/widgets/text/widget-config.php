<?php return [
  "properties" => [
    "label" => "Paragraph", // label for editor
    "class" => "WText", // name of exported class
    "category" => "Text", // editor category
  ],
  "imports" => [
    // widget->class, widget->class, ... (in order)
    "WTextEditor",
  ],
  "files" => [
    "source" => "text.js", // file of class
    "icon" => "paragraph.svg", // file of icon for editor
    "styles" => "text.css" // styles scoped to element
  ]

  // available libraries: Component() -> HTML, widgetBuilder(WidgetDescription) -> HTML representation of widget
];?>