<?php
class OutputManager {
  //protected $output_format = 'xhtml';
  //protected $view_data = array();
  protected $output_manager = null;
  
  public static function get_output_manager_for($output_format) {
    switch ($output_format) {
    case 'xhtml':
      require_once(FRAMEWORK_PATH.'/classes/class.OutputXhtml.php');
      return new OutputXhtml();
    case 'xhtml:gzip':
      require_once(FRAMEWORK_PATH.'/classes/class.OutputXhtml.php');
      $output = new OutputXhtml();
      $output->enable_gzip();
      return $output;
    case 'pdf':
      require_once(FRAMEWORK_PATH.'/classes/class.OutputPdf.php');
      return new OutputPdf();
    case 'pdf:download':
      require_once(FRAMEWORK_PATH.'/classes/class.OutputPdf.php');
      $output = new OutputPdf();
      $output->direct_download();
      return $output;
    default:
      throw new Exception('No output handler for format ['.$output_format.'] defined');
    }
  }
}
?>