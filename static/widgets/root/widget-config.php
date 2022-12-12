<?php return [
  "properties" => [
    "label" => "root", // label for editor
    "class" => "WRoot", // name of exported class
    "category" => "Hidden", // editor category
  ],
  "imports" => [
    // widget->class, widget->class, ... (in order)
  ],
  "files" => [
    "source" => "root.js", // file of class
    "styles" => "root.css", // file of class
  ]
 
  // available libraries: html(HTMLDescription) -> HTML, widgetBuilder(WidgetDescription) -> HTML representation of widget
];?>