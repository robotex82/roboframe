<?php
//require_once('classes/class.View.php');
namespace Mailer;
use Exception;
\Roboframe\Base::enable_module('View\Base');
class Base {
  protected $view_data = array();
  static protected $view_root;
  static protected $configuration_file;
  protected $recipients;
  protected $subject;
  protected $body;
  protected $sender = null;
  protected $settings = array();
  protected $output_format = 'xhtml';
  private   $headers = array();
  private $body_content_type = 'text/plain';
  
    /**
    * Set the html_mail value
    * @param type $html_mail
    */
    public function set_html_mail($html_mail) {
      if($html_mail === true) {
        $this->body_content_type = 'text/html';
      } elseif($html_mail === false) {
        $this->body_content_type = 'text/plain';
      }
    }
  
    /**
    * Returns the body_content_type value.
    * @return type
    */
    public function body_content_type() {
      return $this->body_content_type;
    }
  
    /**
    * Set the headers value
    * @param type $headers
    */
    public function add_header($header) {
      $this->headers[] = $header;
    }
  
    /**
    * Returns the headers value.
    * @return type
    */
    public function headers() {
      return join("\n", $this->headers);
    }
  
  static public function set_view_root($vr) {
    self::$view_root = $vr;
  }
  
  static public function view_root() {
    return self::$view_root;
  }
  
  static public function set_configuration_file($cf) {
    self::$configuration_file = $cf;
  }
  
  static public function configuration_file() {
    return self::$configuration_file;
  }
  
  /*
   * Loads settings from APP_BASE/config/mailer.ini
   */
  public static function load_settings() {
    $environment_name = \Roboframe\Base::environment();
    /*
    if(!$filename) {
      $filename = APP_BASE.'/config/mailer.ini';
    }
    */
    
    if(!($settings = parse_ini_file($filename = self::configuration_file(), true))) {
      throw new Exception('Could not read the Mailer configuration file. '.
                          'Please make sure that there is a proper mailer.ini file at ['.$filename.']!');
    }
    $env_settings = $settings[$environment_name];

    if(!array_key_exists('delivery_method', $env_settings)) {
      throw new Exception('Error in ['.$filename.']. Missing delivery_method directive in settings ['.$environment_name.']');
    }
    
    if(!array_key_exists('host', $env_settings)) {
      throw new Exception('Error in ['.$filename.']. Missing host directive in settings ['.$environment_name.']');
    }
    
    if(!array_key_exists('from_address', $env_settings)) {
      throw new Exception('Error in ['.$filename.']. Missing from_address directive in settings ['.$environment_name.']');
    }
    
    return $env_settings;  
  }
  
  public function __construct() {
    $this->settings = self::load_settings(self::configuration_file());
  }
  
  
  public function __call($method, $args) {
    if(substr($method, 0, 7) == 'render_') {
      $action_name = str_replace('render_', '', $method);
      return $this->render_with_template($action_name);
    }
    
    if(substr($method, 0, 8) == 'deliver_') {
      $deliver_method = str_replace('deliver_', '', $method);
      
      if(substr($deliver_method, -17) == '_with_attachments' && is_array($args[0])) { 
        $deliver_method = str_replace('_with_attachments', '', $deliver_method);
        if(method_exists($this, $deliver_method)) {
          return $this->send_with_attachments($deliver_method, $args[0]);
        }    
      } else {
        if(method_exists($this, $deliver_method)) {
          return $this->send($deliver_method);
        }    
      }
      throw new Exception('Did not find Mailer method ['.$deliver_method.']!');
    }  
  }
  
  private function send($deliver_method) {
    // Execute deliver method
    //$this->$deliver_method();
    $this->body = $this->render_with_template($deliver_method);
//    $this->render_body();
    // load template
      //$view = new View($this->getName(), $action, $this->view_data, $this->get_output_format(), $this->layout);
      //$view->render();    
    // grab output
    // send email
    
    ini_set('SMTP', $this->settings['host']);
    //ini_set('sendmail_from', $this->settings['from_address']);
    //$mail_header = '';
    //$mail_header.= 'from: '.$this->settings['from_address']."\r\n";
    $this->add_header('from: '.$this->settings['from_address']);
    $this->add_header('Content-Type: '.$this->body_content_type());
    //if (mail($this->recipients, $this->subject, $this->body, $mail_header)) {
    if (mail($this->recipients, $this->subject, $this->body, $this->headers())) {
      return true;
    } else {
      return false;
    }
  }
    
  private function send_with_attachments($deliver_method, $files) {
    ini_set("memory_limit","64M");
    ini_set("max_execution_time", 120);

    // Execute deliver method
    //$this->$deliver_method();
    $this->body = $this->render_with_template($deliver_method);
    
    // load template
      //$view = new View($this->getName(), $action, $this->view_data, $this->get_output_format(), $this->layout);
      //$view->render();    
    // grab output
    // send email
    
    ini_set('SMTP', $this->settings['host']);
    //ini_set('sendmail_from', $this->settings['from_address']);
    //$mail_header = '';
    //$mail_header.= 'from: '.$this->settings['from_address']."\r\n";
    $this->add_header('from: '.$this->settings['from_address']);
    
    if(!is_array($files)) {
      throw new Exception('Could not send mail with attachments. No attachments passed!');
    }
    
    $boundary = strtoupper(md5(uniqid(time()))); 
/*
    $mail_header .= "MIME-Version: 1.0";
    $mail_header .= "\nContent-Type: multipart/mixed; boundary=$boundary";
    $mail_header .= "\n\nThis is a multi-part message in MIME format  --  Dies ist eine mehrteilige Nachricht im MIME-Format";
    $mail_header .= "\n--$boundary"; 
    $mail_header .= "\nContent-Type: text/plain";
    $mail_header .= "\nContent-Transfer-Encoding: 8bit";
    $mail_header .= "\n\n$this->body";
*/
    $this->add_header('MIME-Version: 1.0');
    $this->add_header('Content-Type: multipart/mixed; boundary='.$boundary);
    $this->add_header('');
    $this->add_header('This is a multi-part message in MIME format  --  Dies ist eine mehrteilige Nachricht im MIME-Format');
    $this->add_header('--'.$boundary); 
    $this->add_header('Content-Type: '.$this->body_content_type());
    $this->add_header('Content-Transfer-Encoding: 8bit');
    $this->add_header('');
    $this->add_header($this->body);
        
    foreach($files as $file) {
      if(!is_readable($file)) {
        throw new Exception('Could not add file ['.$file.'] as attachment. File is not readable!');
      } else {
        $filename = basename($file);
//        $file_content = fread(fopen($file,"r"),filesize($file));
//        $file_content = chunk_split(base64_encode($file_content));
        $this->add_header('--'.$boundary);
        $this->add_header('Content-Type: application/octetstream; name="'.$filename.'"');
        $this->add_header('Content-Transfer-Encoding: base64');
        $this->add_header('Content-Disposition: attachment; filename="'.$filename.'"');
//        $mail_header .= "\n\n$file_content";
        $this->add_header('');
        $this->add_header(chunk_split(base64_encode(file_get_contents($file))));
      }
    }
    
    $this->add_header('--'.$boundary.'--');
    
    if (mail($this->recipients, $this->subject, $this->body, $mail_header)) {
      return true;
    } else {
      return false;
    }
  }
  
  public function __set($key, $value)  {
    $this->set_var($key, $value);
  }
  
  public function set_var($key, $value) {
    $this->view_data[$key] = $value;
  }
  
  public function recipients($recipients) {
    $this->recipients = $recipients;
  }
  
  public function subject($subject) {
    $this->subject = $subject;
  }
  
  public function body($body) {
    $this->body = $body;
  }
  
  public function output_format($of) {
    $this->output_format = $of;
  }
  
  private function render_with_template($action_name) {
    //$controller_name = \Inflector\Base::underscore(get_class($this));
    $controller_name = \Inflector\Base::underscore(end(explode("\\", get_class($this))));
    $this->$action_name();
//    $view_data = array();
    $layout = '';
    $view = new \View\Base($controller_name, $action_name, $this->view_data, $this->output_format, $layout);
    $view->set_view_root(self::view_root());
    return $view->render(true);
  }
  
  public static function init() {
    spl_autoload_extensions('.php');
    spl_autoload_register('self::class_loader');    
  }
  
  public static function class_loader($namespaced_class) {
    $class = end(explode("\\", $namespaced_class));
    $file = APPLICATION_ROOT.'/models/'.\Inflector\Base::underscore($class).'.php';
    if (!is_readable($file)) {
      return false;
    }
    include $file; 
  }
}