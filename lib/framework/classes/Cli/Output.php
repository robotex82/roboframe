<?php
namespace Cli;
class Output {
  static public function array_to_table($table)
  {
    # add header length to table
    $headers = array_keys($table[0]);

    foreach ($headers as $key => $header)
    {
      $headers[$header] = $header;
      unset($headers[$key]);
    }

    $ttable = $table;
    $ttable[] = $headers;

    # Work out max lengths of each cell
    foreach ($ttable AS $row)
    {
      $cell_count = 0;

      foreach ($row AS $key => $cell)
      {
        $cell_length = strlen($cell);
        $cell_count++;

        if (!isset($cell_lengths[$key]) || $cell_length > $cell_lengths[$key])
        {
          $cell_lengths[$key] = $cell_length;
        }
      }
    }

    # Build header bar
    $bar = '+';
    $header = '|';
    $i = 0;

    foreach ($cell_lengths AS $fieldname => $length)
    {
      $i++;
      $bar .= str_pad('', $length + 2, '-').'+';

      $name = $fieldname;

      if (strlen($name) > $length)
      {
        # crop long headings
        $name = substr($name, 0, $length-1);
      }

      $header .= ' '.str_pad($name, $length, ' ', STR_PAD_BOTH) . ' |';
    }

    $output = "";
    $output .= $bar."\n";
    $output .= $header."\n";
    $output .= $bar."\n";

    # Draw rows
    foreach ($table AS $row)
    {
      $output .= "|";

      foreach ($row AS $key=>$cell)
      {
        $output .= " ".str_pad($cell, $cell_lengths[$key], " ", STR_PAD_RIGHT).' |';
      }

      $output .= "\n";
    }

    $output .= $bar."\n";

    echo $output;
  }
}