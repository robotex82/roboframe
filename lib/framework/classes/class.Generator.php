<?php
class Generator {
  private $_options = array();
  private $revert;
  private $verbose = true;
  
  public function __construct($options = null, $revert = false) {
    if(is_array($options)) {
      array_merge($this->_options, $options);
    }

    $this->revert = $revert;
  }
  
  public function run() {
    (!$this->revert) ? $this->announce('Running') : $this->announce('Reverting');
    
    return $this->commands();
  }
  
  public function revert($revert) {
    $this->revert = $revert;
  }
  
  public function file($source, $target) {
    // check whether the revert flag is set
    if(!$this->revert) {
      // if not begin copying file
      // check if the source file exists
      if(!file_exists($source)) {
        // if not, throw an exeption
        throw new Exception("Could not find source file [{$source}]!");
      }
      // check if target file exists
      if(!file_exists($target)) {
        if(copy($source, $target)) {
          $this->say("+  {$target}");
        } else {
          // throw an Exception if copying fails
          throw new Exception("Could not copy file [{$source}] to [{$target}]!");
        }
      } else {
        // target file exists, ask the user to confirm overwriting the file
        if($this->ask_overwrite($target)) {
          // if user confirms, try to overwrite the file
          if(copy($source, $target)) {
            $this->say("+  {$target}");
          } else {
            // throw an Exception if copying fails
            throw new Exception("Could not copy file [{$source}] to [{$target}]!");
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
          throw new Exception("Could not remove file [{$target}]");
        }
      } else {  
        // if not, ask user to decide
        if($this->ask_delete_modified($target)) {
          // if yes, remove
          if(unlink($target)) {
            $this->say("-  {$target}");
          } else {
            throw new Exception("Could not remove file [{$target}]");
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
  
  public function template($source, $target, array $options) {
      // check whether the revert flag is set
    if(!$this->revert) {
      // map options to local vars
      foreach($options as $key => $value) {
        $$key = $value;
      }
      
      // open file and eval content
      ini_set('implicit_flush', false);
      ob_start(); 
      eval('?>'.file_get_contents($source).'<?');
      // capture evaled code
      $rendered_template = ob_get_contents();
      ob_end_flush(); 
      ini_set('implicit_flush', true);
      
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
        throw new Exception("Could not create file [{$target}]!");
      }
    } else {
      if($this->ask_delete_modified($target)) {
        // if yes, remove
        if(unlink($target)) {
          $this->say("-  {$target}");
        } else {
          throw new Exception("Could not remove file [{$target}]");
        }
      } 
    }

  }
}
?>