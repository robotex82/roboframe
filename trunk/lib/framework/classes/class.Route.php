<?php
class Route {
  /*
   * Default action if no action provided
   */
  private $default_action = 'index';

  /*
   * i.e. 'admin/report/show/:id/:format'
   */
  private $route_template;
  
  /*
   * i.e. array(3 => 'id', 4 => 'format')
   */
  private $route_template_dynamic_parts = array();
  
  /*
   * i.e. array(0 => 'admin', 1 => 'report', 2 => 'show')
   */
  private $route_template_static_parts = array();
  
  /*
   * i.e. array('controller' => 'report', 'action' => 'show')
   */
  private $route_defaults = array();
  
  /*
   * calculated params after matching and parsing
   * i.e. array('id' => '5', 'format' => 'pdf')
   */
  private $request_params = array();
  
  /*
   * i.e. array(0 => 'admin', 1 => 'report', 2 => 'show', 3 => '5', 4 => 'pdf')
   */
  private $request_url = array();
  
  /*
   * i.e. 'report'
   */
  private $controller_name;
  
  /*
   * i.e. 'show'
   */
  private $action_name;  
  
  /*
   * params for url creation
   */
  private $build_url_params = array();  
  
  
  private function add_to_build_url_params($key, $value) {
    $this->build_url_params[$key] = $value;
  }
  
  private function get_build_url_params() {
    return $this->build_url_params;
  }
  
  /**
   * Sets the request URL. Is called in the constructor only
   */
  private function set_request_url(array $url) {
//echo 'Request URL:';
//print_r($url);
    $this->request_url = $url;
  }
  
  private function get_request_url() {
    return $this->request_url;
  }
  
  private function get_part_from_request_url($position) {
//echo 'Getting part ['.$position.'] from request URL. Value ['.$this->request_url[$position].']';
    if(array_key_exists($position, $this->request_url)) {
//echo 'Value ['.$this->request_url[$position].']';
      return $this->request_url[$position];
    }  
  }
  
  /**
   * returns the route defaults.
   */
  private function get_route_defaults() {
    return $this->route_defaults;
  }
  
  /**
   * Sets the route defaults.
   */
  private function set_route_defaults(array $route_defaults) {
//echo 'Route defaults:';
//print_r($route_defaults);
    $this->route_defaults = $route_defaults;
  }
  
  /**
   * Sets the route tempalte. Sets the dynamic parts too.
   */
  private function set_route_template($route_template) {
    $route_template_parts = explode('/', $route_template);
    $position = 0;
    foreach($route_template_parts as $part) {
      if(substr($part, 0, 1) == ':') {
        $this->route_template_dynamic_parts[$position] = substr($part, 1);
      } else {
        $this->route_template_static_parts[$position] = $part;
      }
      $position++;
    }
//echo 'Route template dynamic parts:';
//print_r($this->route_template_dynamic_parts);
//echo 'Route template static parts:';
//print_r($this->route_template_static_parts);
    $this->route_template = $route_template;
  }
  
  private function get_route_template() {
    return $this->route_template;
  }
  
  private function route_template_part_count() {
    return count(explode('/', $this->get_route_template()));
  }
  
  private function request_url_part_count() {
    return count($this->get_request_url());
  }
  
  /**
   * adds an entry to the route defaults.
   */
  private function add_to_route_defaults($key, $value) {
    $this->route_defaults[$key] = $value;
  }
  
  /**
   * Sets the controller name. Is called after succesfully matching the URL against the route
   */
  private function set_controller_name($controller_name) {
    $this->controller_name = $controller_name;  
  }
  
  /**
   * Sets the action name. Is called after succesfully matching the URL against the route
   */
  private function set_action_name($action_name) {
    $this->action_name = $action_name;  
  }
  
  /**
   * Adds a parameter to the request param array.
   */
  private function add_to_request_params($key, $value) {
    $this->request_params[$key] = $value;
  }
  
  /**
   * Splits a URL at the slashes into parts. Returns an array with parts
   */
  public static function split_url($url) {
    return explode('/', $url);
  }

  /**
   * First param should be a valid route template like 'admin/report/show/:id/:format'
   * Second param should be the route defaults like array('controller' => 'report', 'action' => 'show')  
   */
  public function __construct($route_template, array $route_defaults) {
    $this->set_route_template($route_template);
    $this->set_route_defaults($route_defaults);
/*
    if(!array_key_exists('action', $this->get_route_defaults())) {
      $this->add_to_route_defaults('action', 'index');
    }  
*/    
  }
  
  public function match_params(array $params) {
/*
    $params = func_get_args();
    
    if(func_num_args() == 1 and is_array($params[0])) {
      $params = $params[0];
    }

    // process params into an array
    foreach($params as $p) {
      $p_parts = explode(':', $p);
      $this->add_to_build_url_params($p_parts[0], $p_parts[1]);
    }
*/
    $this->clear_build_url_params();
    foreach($params as $key => $value) {
      $this->add_to_build_url_params($key, $value);    
    }


    $build_url_params = $this->get_build_url_params();
    
    if(count($build_url_params) > count($this->route_template_dynamic_parts)) {
      return false;
    }
    
    // check if all build_url_params keys exists in the route template
    foreach($build_url_params as $key => $value) {
      if(!in_array($key, $this->route_template_dynamic_parts)) {
        return false;
      }
    }
//print_r($params);
    // foreach dynamic part in the template, check if there is a counterpart in the url build params,
    // if not, check, if there more dynamic parts at higher positions. if yes, route doesn't match.
    $missing_key = false;
//    $i = 0;
    foreach($this->route_template_dynamic_parts as $position => $value) {
//    echo "Position:".$position.", Value: ".$value.", Missing Key: ".$missing_key."\r\n";
      //if($missing_key) {
      if($missing_key) {
//        echo "Returning...\r\n";
        return false;
      }
      if(!array_key_exists($value, $build_url_params)) {
       $missing_key = true;
      }
      //$i++;
    }
//echo "TRUE";
    return true;
  }
  
  public function build_url() {
    $build_url_params = $this->get_build_url_params();
    $route_template = $this->get_route_template();
    $route_template_parts = explode('/', $route_template);
    
    $url = '';

    foreach($route_template_parts as $part) {
      if(substr($part, 0, 1) == ':') {
//echo substr($part, 1);
//print_r($build_url_params);
        if(isset($build_url_params[substr($part, 1)])) {
          $url.=$build_url_params[substr($part, 1)];
        }
//        $url.= $build_url_params[substr($part, 1)].'/';
        $url.='/';
      } else {
        $url.= $part.'/';
      }
    }
    $url = trim($url, '/');
    /*
    // Foreach build_url_params replace the counterpart in the template
    foreach($build_url_params as $key => $value) {
      $url = str_replace(':'.$key, $value, $url);
    }
    */
    
    // prepend base_url
    
    // return url
    return $url;
  }
  
  /**
   * Matches the passed URL to this route. 
   * Returns true if the route matches
   */
  public function match_url($url) {
    $this->set_request_url(Route::split_url($url));
    
    // if the request url hast more parts then the route template, it can't match
//echo "Checking [".$this->request_url_part_count()."] against [".$this->route_template_part_count()."]\r\n";
    if($this->request_url_part_count() > $this->route_template_part_count()) {
      return false;
    }
    
    // static parts of the route must match their counterparts in the request URL
    if(!$this->match_static_route_parts_with_request_url()) {
      return false;
    }
    
    //map dynamic route params to the request and fill the request params
    $this->extract_request_params_from_request_url();
    
    //fill request from route defaults
    $this->extract_request_params_from_route_defaults();
/*    
    if(!array_key_exists('action', $this->get_route_defaults())) {
      $this->add_to_route_defaults('action', 'index');      
      //$this->set_action_name('index');
    }  
*/    
    $this->set_controller_name($this->request_params['controller']);
    $this->set_action_name($this->request_params['action']);
    
    unset($this->request_params['controller']);
    unset($this->request_params['action']);
    
    return true;
    
  }
  
  public function match_static_route_parts_with_request_url() {
    $request_url = $this->get_request_url();
    foreach($this->route_template_static_parts as $position => $route_part) {
//echo "Checking [".$request_url[$position]."] against [".$route_part."]\r\n";
      if(!isset($request_url[$position])) {
        return false;
      }

      if($request_url[$position] != $route_part) {
        return false;
      }
    }
    return true;
  }
  
  public function extract_request_params_from_request_url() {
    foreach($this->route_template_dynamic_parts as $position => $name) {
//echo 'Position ['.$position.'], name ['.$name.']'."\r\n";      
      $this->add_to_request_params($name, $this->get_part_from_request_url($position));
    }
  }
  
  public function extract_request_params_from_route_defaults() {
    foreach($this->get_route_defaults() as $name => $value) {
      $this->add_to_request_params($name, $value);
    }
  }
  
  /**
   * Returns the controller name, if the route has matched
   */
  public function get_controller_name() {
//    return $this->request_params['controller'];
    return $this->controller_name;
  }
  
  /**
   * Returns the action name, if the route has matched
   */
  public function get_action_name() {
//    return $this->request_params['action'];
    if(empty($this->action_name)) {
      return 'index';
    }
    return $this->action_name;
  }
  
  /**
   * Returns the calculated request parameters, if the route has matched
   */
  public function get_request_params() {
    return $this->request_params;
  }

  private function clear_build_url_params() {
    $this->build_url_params = array();
  }
}
?>