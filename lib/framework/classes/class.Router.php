<?php
class Router {
  private $url;
  private $request_params = array();
  private $routes = array();
  private $controller_name;
  private $action_name;  

  public function get_route_count() { return count($this->routes); }
  
  public function set_url($url) { $this->url = $url; }
  public function get_url() { return $this->url; }
  
  public function set_request_params(array $request_params) { $this->request_params = $request_params; }
  public function get_request_params() { return $this->request_params; }
  
  public function set_controller_name($controller_name) { $this->controller_name = $controller_name; }
  public function get_controller_name() { return $this->controller_name; }
  
  public function set_action_name($action_name) { $this->action_name = $action_name; }
  public function get_action_name() { return $this->action_name; }
  
  public function __construct($url = false, $routes_filename = false) {
    $this->load_routes($routes_filename);
    $this->set_url($url);
  }
  
  public function load_routes($routes_filename = false) {
    $routes = Router::load_settings($routes_filename);
    $position = 0;
    foreach($routes as $route) {
      if(!array_key_exists('template', $route)) {
        throw new Exception('Error in routes.ini file. Missing template directive in route ['.$position.']');
      }
      
      $route_template = $route['template'];
      unset($route['template']);
      $route_defaults = $route;
      $this->routes[] = new Route($route_template, $route_defaults);
      $position++;
    }    
  }
 
  /*
   * Loads routes from APP_BASE/config/routes.ini
   */
  public static function load_settings($filename = false) {
    if(!$filename) {
      $filename = APP_BASE.'/config/routes.ini';
    }
    
    if(!($routes = parse_ini_file($filename, true))) {
      throw new Exception('Could not read the routes configuration file. '.
                          'Please make sure that there is a proper routes file ['.$filename.']!');
    }
    return $routes;  
  }
  
  public function match_all_routes() {
    foreach($this->routes as $route) {
//  print_r($route);
      if($route->match_url($this->get_url())) {
        $this->set_controller_name($route->get_controller_name());
        $this->set_action_name($route->get_action_name());
        $this->set_request_params($route->get_request_params());
        
        return true;
      }
    }      
    throw new Exception('Tried all routes ['.$this->get_route_count().'] on URL ['.$this->get_url().']. Did not match!');
  }
  
  public function url_for(array $url_params) {
//    $url_params = func_get_args();
//print_r($url_params);
    foreach($this->routes as $route) {
      if($route->match_params($url_params)) {
        return $route->build_url();
      }
    }      
    throw new Exception('Could not build URL from params ['.join('|', $url_params).']!');
  }
  
  public static function base_url($dispatcher_url = false, $dispatcher_filename = false) {
    if(!$dispatcher_url) {
      $dispatcher_url = $_SERVER['SCRIPT_NAME'];
    }
    
    if(!$dispatcher_filename) {
      $dispatcher_filename = $_SERVER['SCRIPT_FILENAME'];
    }
    return str_replace('/'.basename($dispatcher_filename), '', $dispatcher_url).'/';
  }
}
?>