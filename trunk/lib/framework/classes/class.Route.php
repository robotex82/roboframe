<?php
class Route {
  private $route_data;
//  private $controller_name;
//  private $action_name;  
  private $url_params = array();    

  public function __construct($route_data) {
    $this->set_route_data($route_data); 
  }  
  
  public function set_route_data(array $route_data) {
    $this->route_data_is_valid($route_data);
    $this->route_data = $route_data; 
  }
  
  public function get_route_data() { 
    return $this->route_data; 
  }
  
  public function get_route_data_url() { 
    return $this->route_data['url']; 
  }
  
  public function get_route_data_url_dynamic_part_count() { 
    $route_data_url_parts = explode('/', $this->get_route_data_url());
    $dynamic_part_count = 0;
    foreach($route_data_url_parts as $route_data_url_part) {
      if(substr($route_data_url_part, 0, 1) == ":") {
        $dynamic_part_count++;
      }
    }  
    return $dynamic_part_count;
  }
  
  public function get_url_params_count() {
    return count($this->url_params);
  }
  
  public function set_controller_name($controller_name) {
    $this->add_url_param('controller', $controller_name);
  }
 
  public function get_controller_name() {
    return $this->get_url_param('controller'); 
  }

  public function set_action_name($action_name) {
    $this->add_url_param('action', $action_name);
  }

  public function get_action_name() {
    return $this->get_url_param('action'); 
  }
/*  
  public function set_url_params(array $url_params) {
    $this->url_params = $url_params;
  }
*/  
  public function add_url_param($key, $value) {
    $this->url_params[$key] = $value;
  }
  
  public function get_url_params() {
    return $this->url_params; 
  }
  
  public function get_url_param($key) {
    if(array_key_exists($key, $this->url_params)) {
      return $this->url_params[$key]; 
    }
  }
  
  public function route_data_is_valid($route_data) {
    if(!array_key_exists('url', $route_data)) {
      throw new Exception('Missing [url] directive in [routes.ini]'); 
    }
    
    if(!array_key_exists('controller', $route_data)) {
      throw new Exception('Missing [controller] directive in [routes.ini]'); 
    }
    
    if(!array_key_exists('action', $route_data)) {
      throw new Exception('Missing [action] directive in [routes.ini]'); 
    }
    
    return true;
  }  
  
  public function match($request_url) {
    if(empty($request_url) and empty($this->route_data['url'])) {
      $this->set_controller_name($this->route_data['controller']);
      $this->set_action_name($this->route_data['action']);
      return true;
    }
    
    $request_url_parts = explode('/', $request_url);
    $route_data_url_parts = explode('/', $this->route_data['url']);
    
    if(count($request_url_parts) != count($route_data_url_parts)) {
      return false;    
    }
    
    $url_part = 0;
    foreach($route_data_url_parts as $route_data_url_part) {
    //echo 'Processing: '.$route_data_url_part;
      if(substr($route_data_url_part, 0, 1) == ":") {
        $param_key = substr($route_data_url_part, 1);
        $this->add_url_param($param_key, $request_url_parts[$url_part]);
        //echo 'Mapping '.$param_key.' => '.$request_url_parts[$url_part];
      }
      $url_part++;
    }
    
    if($this->get_route_data_url_dynamic_part_count() != $this->get_url_params_count()) {
      return false;
    }
    
    return true;
    /*
    $regexp = '/^:(.*)\/:(.*)\/:(.*)$/';
    if(preg_match($regexp, $url, $matches)) {
      return true;
    }
    */
  }
  
}
?>