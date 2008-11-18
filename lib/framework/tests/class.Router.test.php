<?php
class TestOfRouterClass extends UnitTestCase {

  function __contruct() {
    $this->UnitTestCase('Router Class Test');
  }
  
  function setUp() {
  }
  
  function tearDown() {
  }
  
  function test_load_routes_returns_array() {
    $filename = dirname(__FILE__).'/../test_assets/Router/default_routes.ini';
    $this->assertTrue(is_array(Router::load_settings($filename)), 'Load Settings should return an array');
  }
   
  function test_settings_array_includes_needed_keys() {
    $filename = dirname(__FILE__).'/../test_assets/Router/default_routes.ini';
    $routes = Router::load_settings($filename);
    foreach($routes as $route) {
      $this->assertTrue(array_key_exists('template', $route), 'Each route should include [url]');
    }
  }
  
  function test_loding_settings_throw_no_exception() {
    $routes_filename = dirname(__FILE__).'/../test_assets/Router/default_routes.ini';
    $r = new Router('/', $routes_filename);
    $this->assertIsA($r, 'router');
  }
  
  function test_incomplete_settings_throw_exception() {
    $wrong_filename = dirname(__FILE__).'/../test_assets/Router/wrong_routes.ini';

    $this->expectException('Exception', 'Attempting to load wrong routes.ini file should throw an exception');    
    $r = new Router('/', $wrong_filename);

  }
 
  function test_match_valid_url() {
    $routes_filename = dirname(__FILE__).'/../test_assets/Router/default_routes.ini';
    $url = 'blog/show/1';
    $url_parts = explode('/', $url);
    
    $r = new Router($url, $routes_filename);
    $this->assertTrue($r->match_all_routes(), 'Router should match route on routable URL ['.$url.']');
    $request_params = $r->get_request_params();
    $this->assertEqual($r->get_controller_name(), $url_parts[0], 
                       'Controller in Router object ['.$r->get_controller_name().'] should be the same as from mapped URL data ['.$url_parts[0].'] if Route matches');
    $this->assertEqual($r->get_action_name(), $url_parts[1], 
                       'Action in Router object ['.$r->get_action_name().'] should be the same as from mapped URL data ['.$url_parts[1].'] if Route matches');
    $this->assertEqual($request_params['id'], '1', 'Request params should include mapped values. Missing [id=1] for URL ['.$test_url_2.'] and template ['.$route_template.']');


    //$this->assertEqual($r->get_request_params(), $request_params, 'Request params ');                       
  }
  
  function test_match_home_route() {
    $routes_filename = dirname(__FILE__).'/../test_assets/Router/home_routes.ini';
    $url = '';
    
    $r = new Router($url, $routes_filename);
    $this->assertTrue($r->match_all_routes(), 'Router should match route on routable URL ['.$url.']');
    $request_params = $r->get_request_params();
    $this->assertEqual($r->get_controller_name(), 'home', 
                       'Controller in Router object ['.$r->get_controller_name().'] should be the same as from mapped URL data [home] if Route matches');
    $this->assertEqual($r->get_action_name(), 'index', 
                       'Action in Router object ['.$r->get_action_name().'] should be the same as from mapped URL data [index] if Route matches');
  }

}
?>