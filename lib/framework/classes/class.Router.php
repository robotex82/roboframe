<?php
class Router {
  private $url;
  private $url_params = array();
  private $routes = array();
  private $controller_name;
  private $action_name;  

  public function get_route_count() { return count($this->routes); }
  
  public function set_url($url) { $this->url = $url }
  public function get_url() { return $this->url; }
  
  public function set_controller_name($controller_name) { $this->controller_name = $controller_name }
  public function get_controller_name() { return $this->controller_name; }
  
  public function set_action_name($action_name) { $this->action_name = $action_name }
  public function get_action_name() { return $this->action_name; }
  
  public function __construct($url) { $this->set_url($url); }
 
  /*
   * Loads routes from APP_BASE/config/routes.ini
   */
  public static function load_routes() {
    if(!($routes = parse_ini_file(APP_BASE.'/config/database.ini', true))) {
      throw new Exception('Could not read the routes configuration file. '.
                          'Please make sure that there is a proper routes.ini file in the '.APP_BASE.'/config folder!');
    }
    
    foreach($routes as $route) {
      $this->routes[] = new Route($route);
    }
    return true;  
  }
  
  public function match_all_routes() {
    foreach($this->routes as $route) {
      if($route->match($url)) {
        $this->set_controller_name($route->get_controller_name());
        $this->set_action_name($route->get_action_name());
        $this->set_url_params($route->get_mapped_url_params());
        
        return true;
      }
      
      throw new Exception('Tried all routes ['.$this->get_route_count().'] on URL ['.$this->get_url().']. Did not match!');
    }
  }
}
?>