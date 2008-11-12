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
  private $request_params;
  
  /*
   * i.e. array(0 => 'admin', 1 => 'report', 2 => 'show', 3 => '5', 4 => 'pdf')
   */
  private $request_url;

  /**
   * Sets the request URL. Is called in the constructor only
   */
  private function set_request_url(array $url) {
    echo 'Request URL:';
    print_r($url);
    $this->request_url = $url;
  }
  
  private function get_request_url() {
    return $this->request_url;
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
    echo 'Route defaults:';
    print_r($route_defaults);
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
    echo 'Route template dynamic parts:';
    print_r($this->route_template_dynamic_parts);
    echo 'Route template static parts:';
    print_r($this->route_template_static_parts);
    $this->route_template = $route_template;
  }
  
  private function get_route_template() {
    return $this->route_template;
  }
  
  private function route_template_part_count() {
    return count(explode('/', $this->get_route_template()));
  }
  
  private function request_url_part_count() {
    return count(explode('/', $this->get_request_url()));
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
  private function add_request_param(string $key, string $value) {
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
    if(!array_key_exists('action', $this->get_route_defaults())) {
      $this->add_to_route_defaults('action', 'index');      
    }  
  }
  
  /**
   * Matches the passed URL to this route. 
   * Returns true if the route matches
   */
  public function match_url($url) {
    $this->set_request_url(Route::split_url($url));
    
    // if the request url hast more parts then the route template, it can't match
    if($this->request_url_part_count() > $this->route_template_part_count()) {
      return false;
    }
  }
  
  /**
   * Returns the controller name, if the route has matched
   */
  public function get_controller_name() {}
  
  /**
   * Returns the action name, if the route has matched
   */
  public function get_action_name() {}
  
  /**
   * Returns the calculated request parameters, if the route has matched
   */
  public function get_request_params() {}

}
?>