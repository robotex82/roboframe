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
  
  public function __construct($url, $routes_filename = false) {
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
}
?>