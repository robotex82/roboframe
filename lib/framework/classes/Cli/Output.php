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
  
  /**
   * show a status bar in the console
   * 
   * <code>
   * for($x=1;$x<=100;$x++){
   * 
   *     show_status($x, 100);
   * 
   *     usleep(100000);
   *                           
   * }
   * </code>
   *
   * @param   int     $done   how many items are completed
   * @param   int     $total  how many items are to be done total
   * @param   int     $size   optional size of the status bar
   * @return  void
   *
   */
  public static function show_status($done, $total, $size=30) {

    static $start_time;

    // if we go over our bound, just ignore it
    if($done > $total) return;

    if(empty($start_time)) $start_time=time();
    $now = time();

    $perc=(double)($done/$total);

    $bar=floor($perc*$size);

    $status_bar="\r[";
    $status_bar.=str_repeat("=", $bar);
    if($bar<$size){
      $status_bar.=">";
      $status_bar.=str_repeat(" ", $size-$bar);
    } else {
      $status_bar.="=";
    }

    $disp=number_format($perc*100, 0);

    $status_bar.="] $disp%  $done/$total";

    $rate = ($now-$start_time)/$done;
    $left = $total - $done;
    $eta = round($rate * $left, 2);

    $elapsed = $now - $start_time;

    $status_bar.= " remaining: ".number_format($eta)." sec.  elapsed: ".number_format($elapsed)." sec.";

    echo "$status_bar  ";

    flush();

    // when done, send a newline
    if($done == $total) {
      echo "\n";
    }
  }
}