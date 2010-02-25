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
  }
  
  function test_empty_request_url() {
    $url = '';
    //$route_template = '/';
    $route_template = '';
    $route_defaults = array('controller' => 'home', 'action' => 'index');
    
    $r = new Route($route_template, $route_defaults);
    
    $this->assertIsA($r, 'Route');
    
    $this->assertTrue($r->match_url($url), 'URL ['.$url.'] should match route template ['.$route_template.']');
    $this->assertEqual($r->get_controller_name(), 'home', 'Controller name from route object should take route defaults on empty route and home route definition.');
    $this->assertEqual($r->get_action_name(), 'index', 'Action name from route object should take route defaults on empty route and home route definition.');
  }
  
  function test_default_route() {
    $route_template = ':controller/:action/:id';
    $route_defaults = array();
    $test_url = 'blog/show/1'; // Should match.
    $test_url_without_id = 'blog/list'; // Should match. params['id'] should be unset
    $test_url_without_action_and_id = 'blog'; // Should match. Action should be 'index', params['id'] should be unset
    $test_empty_url = ''; // Should no match
    
    $r = new Route($route_template, $route_defaults);
    
    $this->assertIsA($r, 'Route');
    
    $this->assertTrue($r->match_url($test_url), 'URL ['.$test_url.'] should match route template ['.$route_template.']');
    
    $this->assertTrue($r->match_url($test_url_without_id), 'URL ['.$test_url_without_id.'] should match route template ['.$route_template.']');
  }  

  function test_routes_with_static_parts() {
    $route_template = 'admin/report/show/:id/:format';
    $route_defaults = array('controller' => 'report', 'action' => 'show');
    $test_url = 'admin/report/show/5/pdf'; // Should match.
    $test_url_without_format = 'admin/report/show/5'; // Should match. params['format'] should be unset
    $test_url_without_id_and_format = 'admin/report/show'; // Should match. Action should be 'index', params['id'] and params['format'] should be unset
    $test_empty_url = ''; // Should no match
    $other_url = 'other/url/with/show'; // Should not match
    $too_long_url = 'admin/report/show/5/pdf/other/param'; // Should not match
    
    $r = new Route($route_template, $route_defaults);
    
    $this->assertIsA($r, 'Route');
    
    $this->assertTrue($r->match_url($test_url), 'URL ['.$test_url.'] should match route template ['.$route_template.']');
    
    $this->assertTrue($r->match_url($test_url_without_format), 'URL ['.$test_url_without_format.'] should match route template ['.$route_template.']');
    
    $this->assertTrue($r->match_url($test_url_without_id_and_format), 'URL ['.$test_url_without_id_and_format.'] should match route template ['.$route_template.']');
    
    $this->assertFalse($r->match_url($other_url), 'URL ['.$other_url.'] should not match route template ['.$route_template.']');

    $this->assertFalse($r->match_url($too_long_url), 'URL ['.$too_long_url.'] should not match route template ['.$route_template.']');
  }
  
  function test_request_params() {
    $route_template = 'admin/report/show/:id/:format';
    $route_defaults = array('controller' => 'report', 'action' => 'show');
    $test_url_1 = 'admin/report/show/5/pdf'; // Should match.

    
    $r = new Route($route_template, $route_defaults);
    $this->assertIsA($r, 'Route');
    $r->match_url($test_url_1);
    $request_params = $r->get_request_params();
//    $this->assertEqual($request_params['controller'], 'report');
//    $this->assertEqual($request_params['action'], 'show');
    $this->assertEqual($request_params['id'], '5');
    $this->assertEqual($request_params['format'], 'pdf');

    
    $route_template = ':controller/:action/:id';
    $route_defaults = array();
    $test_url_2 = 'blog/show/1'; // Should match.
    
    $r = new Route($route_template, $route_defaults);
    $this->assertIsA($r, 'Route');
    $r->match_url($test_url_2);
    $request_params = $r->get_request_params();
//    $this->assertEqual($request_params['controller'], 'blog');
//    $this->assertEqual($request_params['action'], 'show');
    $this->assertEqual($request_params['id'], '1');
  }
 
  function test_controller_action_and_params_are_filled_correctly() {
    $route_template = 'admin/report/show/:id/:format';
    $route_defaults = array('controller' => 'report', 'action' => 'show');
    $test_url_1 = 'admin/report/show/5/pdf'; // Should match.

    
    $r = new Route($route_template, $route_defaults);
    $this->assertIsA($r, 'Route');
    $r->match_url($test_url_1);
    $this->assertEqual($r->get_controller_name(), 'report', 'Controller name from route object should equal controller name from route details if given.');
    $this->assertEqual($r->get_action_name(), 'show', 'Action name from route object should equal action name from route details if given.');
    
    $request_params = $r->get_request_params();
    $this->assertEqual($request_params['id'], '5', 'Request params should include mapped values. Missing [id=5] for URL ['.$test_url_1.'] and template ['.$route_template.']');
    $this->assertEqual($request_params['format'], 'pdf', 'Request params should include mapped values. Missing [format=pdf] for URL ['.$test_url_1.'] and template ['.$route_template.']');

    
    $route_template = ':controller/:action/:id';
    $route_defaults = array();
    $test_url_2 = 'blog/show/1'; // Should match.
    
    $r = new Route($route_template, $route_defaults);
    $this->assertIsA($r, 'Route');
    $r->match_url($test_url_2);
    $request_params = $r->get_request_params();
    $this->assertEqual($r->get_controller_name(), 'blog', 'Controller name from request URL should equal controller name in route object if route matches.');
    $this->assertEqual($r->get_action_name(), 'show', 'Action name from request URL should equal action name in route object if route matches.');
    $this->assertEqual($request_params['id'], '1', 'Request params should include mapped values. Missing [id=1] for URL ['.$test_url_2.'] and template ['.$route_template.']');
  }
  
  function test_controller_and_action_are_omitted_in_params() {
    $route_template = ':controller/:action/:id';
    $route_defaults = array();
    $test_url_2 = 'blog/show/1'; // Should match.
    
    $r = new Route($route_template, $route_defaults);
    $this->assertIsA($r, 'Route');
    $r->match_url($test_url_2);
    $request_params = $r->get_request_params();
    $this->assertFalse(array_key_exists('controller', $request_params), 'Request params should not include [controller] field');
    $this->assertFalse(array_key_exists('action', $request_params), 'Request params should not include [action] field');
  }
  
  function test_match_params() {
    $route_template = ':controller/:action/:id';
    $route_defaults = array();
    $test_url = 'blog/show/1';
    $url_params = array( 'controller'=>'blog', 'action'=>'show', 'id'=>'1' );
    
    $r = new Route($route_template, $route_defaults);  
    $this->assertIsA($r, 'Route');
    
    $this->assertTrue($r->match_params($url_params), 'Route ['.$route_template.'] should match params ['.join('|', $url_params).']');
    $url = $r->build_url();
    $this->assertEqual($url, $test_url, 'URL from Route ['.$url.'] must match test URL ['.$test_url.'] if params match');

    $test_url = 'blog/show';
    $url_params = array( 'controller'=>'blog', 'action'=>'show' );
    
    $r = new Route($route_template, $route_defaults);  
    $this->assertIsA($r, 'Route');
    
    $this->assertTrue($r->match_params($url_params), 'Route ['.$route_template.'] should match params ['.join('|', $url_params).']');
    $url = $r->build_url();
    $this->assertEqual($url, $test_url, 'URL from Route ['.$url.'] must match test URL ['.$test_url.'] if params match');
    
    
    $route_template = 'admin/report/show/:id/:format';
    $route_defaults = array('controller' => 'report', 'action' => 'show');
    $test_url = 'admin/report/show/5/pdf'; // Should match.
    $url_params = array( 'id'=>'5', 'format'=>'pdf' );
    
    $r = new Route($route_template, $route_defaults);  
    $this->assertIsA($r, 'Route');
    
    $this->assertTrue($r->match_params($url_params), 'Route ['.$route_template.'] should match params ['.join('|', $url_params).']');
    $url = $r->build_url();
    $this->assertEqual($url, $test_url, 'URL from Route ['.$url.'] must match test URL ['.$test_url.'] if params match');
    
    $route_template = 'admin/report/show/:id/:format/static';
    $route_defaults = array('controller' => 'report', 'action' => 'show');
    $test_url = 'admin/report/show/5/pdf/static'; // Should match.
    $url_params = array( 'id'=>'5', 'format'=>'pdf' );
    
    $r = new Route($route_template, $route_defaults);  
    $this->assertIsA($r, 'Route');
    
    $this->assertTrue($r->match_params($url_params), 'Route ['.$route_template.'] should match params ['.join('|', $url_params).']');
    $url = $r->build_url();
    $this->assertEqual($url, $test_url, 'URL from Route ['.$url.'] must match test URL ['.$test_url.'] if params match');
  }
  
  function test_match_params_doesnt_match_on_missing_middle_param() {
    $route_template = ':controller/:action/:id';
    $route_defaults = array();
    $test_url = 'blog/show/1';
    $url_params = array( 'controller'=>'blog', 'id'=>'1' );
    
    $r = new Route($route_template, $route_defaults);  
    $this->assertIsA($r, 'Route');
    
    $this->assertFalse($r->match_params($url_params), 'Route ['.$route_template.'] should not match params ['.join('|', $url_params).']');
  }
/* */  
}  
?>