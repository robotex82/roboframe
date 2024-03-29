<?php
namespace Generator;
use Inflector\Base as Inflector;
/**
 * 
 * @author Roberto Vasquez Angel
 * 
 *  Usage:
 * 
 *  <p>Create a file called generator.php in a new folder. This class contains the Generator class that 
 *  extends the Generator\Base class and implements the commands method.</p>
 * 
 *  Example generator.php:
 *  <code>
 *  namespace Generator;
 *  class Authentication extends Base {
 *    public function commands() {
 *    }
 *  }
 *  </code>
 * 
 *  <p>The commands() method gets called by the Generator runner, when the generator is invoked. It should
 *  contain all actions that the generator has to perform.</p>
 * 
 *  <p>It is a good idea to define a tempalte root, where you store all templates and sourcefiles for the generator:</p>
 *  
 *  <code>
 *  $this->template_root = dirname(__FILE__).'/templates';
 *  </code>
 *  
 *  Passing options to the generator
 *  
 *  <p>It you define the options array in your generator, you can map passed options to names instead of numbers</p>
 *  
 *  <p>Example:</p>
 *  
 *  <code>
 *  public $option_mappings = array(0 => 'app_name');
 *  </code>
 *  
 *  Registering a generator
 *  
 *  <p>You can register a generator by passing the name and path to the register method of the Generator Manager</p>
 *  
 *  Example:
 *  
 *  <code>\Generator\Manager\Base::register_generator('<GENERATOR_NAME>', '<PATH_TO_THE_GENERATOR>');</code>
 *  
 *  
 *  Comands
 *  
 *  <ul>
 *    <li>directory(string $path)</li>
 *    <li>file(string $source, string $target)</li>
 *    <li>template(string $source, string $target, array $options)</li>
 *  </ul>  
 *    
 */
class Base {
  private $_options = array();
  private $revert;
  private $verbose = true;
  public  $option_mappings = array();
  
  public function __construct($options = null, $revert = false) {
    if(is_array($options)) {
      array_merge($this->_options, $options);
    }

    $this->revert = $revert;
  }
  
  public function map_options($options) {
    for($i = 0; $i < count($options); $i++) {
      $this->set_var($this->option_mappings[$i], $options[$i]);
    }
  }
  
  public function run() {
    (!$this->revert) ? $this->announce('Running') : $this->announce('Reverting');
    
    return $this->commands();
  }
  
  public function revert($revert) {
    $this->revert = $revert;
  }
  
  /**
   * Generates a directory at the given target
   * 
   * @param $target
   */
  public function directory($target) {
    // check whether the revert flag is set
    if(!$this->revert) {
      // check if the directory already exists
      if(is_dir($target)) {
        // target file exists, ask the user to confirm overwriting the directory
        if($this->ask_overwrite($target)) {
          $this->say("+  {$target}");
/*
          // if user confirms, try to overwrite the directory
          if(mkdir($target)) {
            $this->say("+  {$target}");
          } else {
            // throw an Exception if copying fails
            throw new \Exception("Could not create directory [{$target}]!");
          }
*/          
        }  
      } else {
        if(mkdir($target)) {
          $this->say("+  {$target}");
        } else {
          // throw an Exception if copying fails
          throw new \Exception("Could not create directory [{$target}]!");
        }
      }
    } else {
      // check if directory is empty
      if(Generator::directory_is_empty($target)) {
        // if yes, remove target directory
        if(unlink($target)) {
          $this->say("-  {$target}");
        } else {
          throw new \Exception("Could not remove directory [{$target}]");
        }
      } else {  
        // if not, ask user to decide
        if($this->ask_delete_not_empty($target)) {
          // if yes, remove
          if(unlink($target)) {
            $this->say("-  {$target}");
          } else {
            throw new \Exception("Could not remove directory [{$target}]");
          }
        }  
        // else keep it
      }  
    }
  }
  
  /**
   * Copies a file from the source to the target
   * 
   * @param unknown_type $source
   * @param unknown_type $target
   */
  public function file($source, $target) {
    // check whether the revert flag is set
    if(!$this->revert) {
      // if not begin copying file
      // check if the source file exists
      if(!file_exists($source)) {
        // if not, throw an exeption
        throw new \Exception("Could not find source file [{$source}]!");
      }
      // check if target file exists
      if(!file_exists($target)) {
        if(copy($source, $target)) {
          $this->say("+  {$target}");
        } else {
          // throw an Exception if copying fails
          throw new \Exception("Could not copy file [{$source}] to [{$target}]!");
        }
      } else {
        // target file exists, ask the user to confirm overwriting the file
        if($this->ask_overwrite($target)) {
          // if user confirms, try to overwrite the file
          if(copy($source, $target)) {
            $this->say("+  {$target}");
          } else {
            // throw an Exception if copying fails
            throw new \Exception("Could not copy file [{$source}] to [{$target}]!");
          }
        }
      }
    } else {
      // check if source and target file are the same
      if(sha1_file($source) == sha1_file($target)) {
        // if yes, remove target file
        if(unlink($target)) {
          $this->say("-  {$target}");
        } else {
          throw new \Exception("Could not remove file [{$target}]");
        }
      } else {  
        // if not, ask user to decide
        if($this->ask_delete_modified($target)) {
          // if yes, remove
          if(unlink($target)) {
            $this->say("-  {$target}");
          } else {
            throw new \Exception("Could not remove file [{$target}]");
          }
        }  
        // else keep it
      }    
    }

  }
  
  private function ask_overwrite($target) {
    return $this->ask_user("[{$target}] already exists. Overwrite?");
  }
  
  private function ask_delete_modified($target) {
    return $this->ask_user("[{$target}] has been modified. Delete?");  
  }
  
  private function ask_delete_downloaded($target) {
    return $this->ask_user("[{$target}] has been downloaded and might have been modified. Delete?");  
  }
  
  private function ask_delete_not_empty($target) {
    return $this->ask_user("[{$target}] is not empty. Delete?");  
  }
  
  private function ask_user($question) {
    $choices = array('y', 'n');
    $user_input = null;
    // output question
    echo "{$question} [".join($choices)."]";
    
    while(!in_array($user_input, $choices)) {
      // wait for user input
      $user_input = trim(fgets(STDIN));
    }
    switch($user_input) {
      case "y": return true;
      case "n": return false;
    }
  }
  
  private function write($text = '') {
    echo ($this->verbose) ? $text : null;
    echo ($this->verbose) ?  "\r\n": null;
  }
  
  private function say($message, $subitem = false) {
    $output = ($subitem) ? "  -> " : "  ";
    $output.= $message;
    $this->write($output);
  }
  
  private function announce($message) {
    $output = get_class($this).': '.$message;
    $length = strlen($output);
    $this->write($output);
    
    $output = '';
    for($i = 0; $i < $length; $i++) {
      $output.= '=';
    }
    $this->write($output);
  }
  
  /**
   * Generates a new file on the target from a source template and the passed options
   * 
   * @param unknown_type $source
   * @param unknown_type $target
   * @param array $options
   */
  public function template($source, $target, array $options) {
      // check whether the revert flag is set
    if(!$this->revert) {
      // map options to local vars
      foreach($options as $key => $value) {
        $$key = $value;
      }
      
      // open file and eval content
      //ini_set('implicit_flush', false);
      ob_start(); 
      eval('?>'.file_get_contents($source).'<?');
      // capture evaled code
      $rendered_template = ob_get_contents();
      ob_end_flush(); 
      //ini_set('implicit_flush', true);  
      $rendered_template = str_replace('<|?', '<?', $rendered_template);
      $rendered_template = str_replace('?|>', '?>', $rendered_template);
      
      // check if target file exists
      if(file_exists($target)) {
        // ask the user if he wants to overwrite the existing file
        if(!$this->ask_overwrite($target)) {
          // if user denies overwriting the file, return false
          return false;
        }  
      }
      // write to file
      if(file_put_contents($target, $rendered_template)) {
        $this->say("+  {$target}");        
      } else {
        throw new \Exception("Could not create file [{$target}]!");
      }
    } else {
      if($this->ask_delete_modified($target)) {
        // if yes, remove
        if(unlink($target)) {
          $this->say("-  {$target}");
        } else {
          throw new \Exception("Could not remove file [{$target}]");
        }
      } 
    }

  }
  
  public function download($source, $target) {
    // check whether the revert flag is set
    if(!$this->revert) {
      // if not begin copying file
      // check if the source file exists
      /*
      if(!$data = self::curl($source)) {
        // if not, throw an exeption
        throw new \Exception("Could not download source file from [{$source}]!");
      }
      */
      // check if target file exists
      if(!file_exists($target)) {
        if(self::curl($source, $target)) {
          $this->say("+  {$target}");
        } else {
          // throw an Exception if copying fails
          throw new \Exception("Could not download file to [{$target}]!");
        }
      } else {
        // target file exists, ask the user to confirm overwriting the file
        if($this->ask_overwrite($target)) {
          // if user confirms, try to overwrite the file
          if(self::curl($source, $target)) {
            $this->say("+  {$target}");
          } else {
            // throw an Exception if copying fails
            throw new \Exception("Could not download file to [{$target}]!");
          }
        }
      }
    } else {
      // check if source and target file are the same
      /*
      if(sha1_file($source) == sha1_file($target)) {
        // if yes, remove target file
        if(unlink($target)) {
          $this->say("-  {$target}");
        } else {
          throw new Exception("Could not remove file [{$target}]");
        }
      } else {
      */  
        // if not, ask user to decide
        if($this->ask_delete_downloaded($target)) {
          // if yes, remove
          if(unlink($target)) {
            $this->say("-  {$target}");
          } else {
            throw new \Exception("Could not remove file [{$target}]");
          }
        }  
        // else keep it
      //}    
    }

  }
  
  public function __set($key, $value)  {
    $this->set_var($key, $value);
  }
  public function __get($key) {
    return $this->get_var($key);
  } 
  
  private function set_var($key, $value) {
    $this->_options[$key] = $value;
  }
  private function get_var($key) {
    if (array_key_exists($key, $this->_options)) {
      return $this->_options[$key];
    }
  }
  
  static public function extract_generator_options_from_cli($arguments) {
    unset($arguments[0]);
    unset($arguments[1]);
    return array_values($arguments);
  }
/*  
  static public function search_generator($name) {
    if(is_readable(APP_BASE.'/lib/generators/'.$name.'/generator.php')) {
      require_once APP_BASE.'/lib/generators/'.$name.'/generator.php';;
      return APP_BASE.'/lib/generators/'.$name.'/generator.php';
    }

  if(is_readable(FRAMEWORK_PATH.'/generators/'.$name.'/generator.php')) {
      require_once(FRAMEWORK_PATH.'/generators/'.$name.'/generator.php');
      return FRAMEWORK_PATH.'/generators/'.$name.'/generator.php';
    }
  }
*/  
  static public function directory_is_empty($dir) {
    return (($files = @scandir($dir)) && count($files) <= 2); 
  }
  
  static public function curl($uri, $target) {
    // initalize cURL handle.
    $curl = curl_init($uri);
    
    // set cURL verbosity
    curl_setopt($curl, CURLOPT_VERBOSE, 0);
    // tell cURL to get headers.
    curl_setopt($curl, CURLOPT_HEADER, 0);
    // tell cURL to return the result.
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    // Set HTTP Proxy Server
    curl_setopt($curl, CURLOPT_PROXY, \Roboframe\Base::http_proxy());

    // exec cURL command and get result into $response
    $response = curl_exec($curl);

    // get content-server http response headers
    $response_headers = curl_getinfo($curl);
    
    // get response code from the headers 
    $response_code = (string) $response_headers['http_code'];

    // close cURL session
    curl_close($curl);
    
    // if the content-server http response code begins with "2", the request
    // was successful.
    if(substr($response_code, 0, 1) == '0') {
      throw new \Exception('Curl request failed! HTTP response code ['.$response_code.'] for URI ['.$uri.']. You might need a Proxy server!');
    }
    
    if(substr($response_code, 0, 1) != '2') {
      throw new \Exception('Curl request failed! HTTP response code ['.$response_code.'] for URI ['.$uri.']');
    }
    if(!file_put_contents($target, $response)) {
      throw new \Exception('Could not write to file ['.$target.']');
    }
    return true;
  	
  }
}
?>