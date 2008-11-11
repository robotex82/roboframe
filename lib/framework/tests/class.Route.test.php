<?php
class TestOfRouteClass extends UnitTestCase {
  function __contruct() {
    $this->UnitTestCase('Route Class Test');
  }
  
  function setUp() {
  }
  
  function tearDown() {
  }
  
  function test_root_route() {
/*
    $route_data = array('url'        => ':controller/:action/:id'
                       ,'controller' => ':controller'
                       ,'action'     => ':action'
                       ,'id'         => ':id'  
    );
*/    

    $route_data = array('url'        => ''
                       ,'controller' => 'home'
                       ,'action'     => 'index'
    );
    
    $url = '';
    
    $this->assertTrue($r = new Route($route_data), 'Route Object created with simple routing data');
    $this->assertTrue($r->match($url), 'Empty URL ['.$url.'] should match root route URL ['.$route_data['url'].'].');
    $this->assertEqual($r->get_controller_name(), $route_data['controller'], 
                       'Controller in Route object ['.$r->get_controller_name.'] should be the same as from Route data ['.$route_data['controller'].'] if Route matches');
    $this->assertEqual($r->get_action_name(), $route_data['action'], 
                       'Action in Route object ['.$r->get_action_name.'] should be the same as from Route data ['.$route_data['action'].'] if Route matches');
  }
  
  function test_default_route() {
    $route_data = array('url'        => ':controller/:action/:id'
                       ,'controller' => ':controller'
                       ,'action'     => ':action'
                       ,'id'         => ':id'  
    );

    $url = 'blog/show/1';
    $url_parts = explode('/', $url);
    
    $this->assertTrue($r = new Route($route_data), 'Route Object created with simple routing data');
    $this->assertTrue($r->match($url), 'URL ['.$url.'] should match default route URL ['.$route_data['url'].'].');
    $this->assertEqual($r->get_controller_name(), $url_parts[0], 
                       'Controller in Route object ['.$r->get_controller_name().'] should be the same as from mapped URL data ['.$url_parts[0].'] if Route matches');
    $this->assertEqual($r->get_action_name(), $url_parts[1], 
                       'Action in Route object ['.$r->get_action_name().'] should be the same as from mapped URL data ['.$url_parts[1].'] if Route matches');
  }
}  
?>