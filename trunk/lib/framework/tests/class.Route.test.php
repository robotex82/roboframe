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
}  
?>