<?php
class TestOfRouterClass extends UnitTestCase {
/*
  function __contruct() {
    $this->UnitTestCase('Router Class Test');
  }
  
  function setUp() {
  }
  
  function tearDown() {
  }
  
  function test_load_routes_returns_array() {
    $this->assertTrue(is_array(Router::load_settings()), 'Load Settings should return an array');
  }
  
  function test_settings_array_includes_needed_keys() {
    $routes = Router::load_settings();
    foreach($routes as $route) {
      $this->assertTrue(array_key_exists('url', $route), 'Each route should include [url]');
    }
  }
  
  function test_match_valid_url() {
    $url = 'blog/show/1';
    $url_parts = explode('/', $url);
    
    $r = new Router($url);
    $this->assertTrue($r->match_all_routes(), 'Router should match route on routable URL ['.$url.']');
    $this->assertEqual($r->get_controller_name(), $url_parts[0], 
                       'Controller in Router object ['.$r->get_controller_name().'] should be the same as from mapped URL data ['.$url_parts[0].'] if Route matches');
    $this->assertEqual($r->get_action_name(), $url_parts[1], 
                       'Action in Router object ['.$r->get_action_name().'] should be the same as from mapped URL data ['.$url_parts[1].'] if Route matches');
  }
*/
}
?>