<?php return [
  "properties" => [
    "label" => "CommentSection", // label for editor
    "class" => "WCommentSection", // name of exported class
    "category" => "Hidden", // editor category
  ],
  "imports" => [
    "WComment"
    // widget->class, widget->class, ... (in order)
  ],
  "files" => [
    "source" => "comment-section.js", // file of class
    "icon" => "", // file of icon for editor
    "styles" => "comment-section.css" // styles scoped to element
  ]

  // available libraries: Component() -> HTML, widget-core
];?>