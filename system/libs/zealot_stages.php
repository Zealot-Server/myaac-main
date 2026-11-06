<?php

defined('MYAAC') or die('Direct access not allowed!');

function myaac_parse_stages_block($content, $name)
{
  $stages = [];
  $start = strpos($content, $name . ' = {');
  if ($start === false) {
    return $stages;
  }

  $section = substr($content, $start);
  if (
    preg_match(
      '/\n\n[a-zA-Z]+Stages\s*=\s*\{/',
      $section,
      $next,
      PREG_OFFSET_CAPTURE,
      strlen($name)
    )
  ) {
    $section = substr($section, 0, $next[0][1]);
  }

  if (preg_match_all('/\{\s*(.*?)\s*\}/s', $section, $rows)) {
    foreach ($rows[1] as $row) {
      $stage = [
        'minlevel' => null,
        'maxlevel' => null,
        'multiplier' => null,
      ];

      foreach ($stage as $key => $value) {
        if (preg_match('/' . $key . '\s*=\s*([0-9.]+)/', $row, $field)) {
          $stage[$key] = $field[1];
        }
      }

      if ($stage['minlevel'] !== null && $stage['multiplier'] !== null) {
        $stages[] = $stage;
      }
    }
  }

  return $stages;
}
