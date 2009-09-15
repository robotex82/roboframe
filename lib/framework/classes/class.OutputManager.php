<?php
class OutputManager {
  //protected $output_format = 'xhtml';
  //protected $view_data = array();
  protected $output_manager = null;
  
  public static function get_output_manager_for($output_format) {
    $exploded_output_format = explode('|', $output_format);
    switch ($exploded_output_format[0]) {
    case 'csv':
      require_once(FRAMEWORK_PATH.'/classes/class.OutputCsv.php');
      return new OutputCsv();
    case 'xhtml':
      require_once(FRAMEWORK_PATH.'/classes/class.OutputXhtml.php');
      $output = new OutputXhtml();
      if(isset($exploded_output_format[1]) && $exploded_output_format[1] == 'gzip') {
        $output->enable_gzip();
      }
      return $output;
    case 'file':
      require_once(FRAMEWORK_PATH.'/classes/class.OutputFile.php');
      $output = new OutputFile();
      if(isset($exploded_output_format[1])) {
        $output->set_file($exploded_output_format[1]);
      }
      if(isset($exploded_output_format[2])) {
        $output->set_filename($exploded_output_format[2]);
      }
      if(isset($exploded_output_format[3])) {
        $output->set_mimetype($exploded_output_format[3]);
      }
      return $output;
    case 'pdf':
      require_once(FRAMEWORK_PATH.'/classes/class.OutputPdf.php');
      $output = new OutputPdf();
      if($exploded_output_format[1] == 'download') {
        $output->enable_gzip();
      }
      $output->direct_download();
      return $output;
    default:
      throw new Exception('No output handler for format ['.$output_format.'] defined');
    }
  }
}
?>