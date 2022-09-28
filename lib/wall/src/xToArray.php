<?php

  function xToArray ($x, array &$appendTo = null): array {
    $a = isset($appendTo) ? $appendTo : [];
    foreach ($x as $k => $v) {
      $a[$k] = $v;
    }
    return $a;
  }