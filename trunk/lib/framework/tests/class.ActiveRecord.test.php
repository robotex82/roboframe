<?php
require_once(FRAMEWORK_PATH.'/classes/ActiveRecord/class.Base.php');
require_once(dirname(__FILE__).'/../test_assets/ActiveRecord/customer.php');

class TestOfActiveRecordClass extends UnitTestCase {
  function __contruct() {
    $this->UnitTestCase('ActiveRecord Class Test');
  }

  function setUp() {
  }

  function tearDown() {
  }

  function test_simple_crud() {
    $table_name = "tbl_customers";

    Database::drop_table_if_exists($table_name);
    Database::drop_table_if_exists('schema_info');

    if(Database::adapter()->prefetch_primary_key()) {
      Database::drop_sequence($table_name.'_seq');
    }


    require_once(dirname(__FILE__).'/../test_assets/ActiveRecord/migrations/001_create_customer_table.php');
    $m = new CreateCustomerTable(APP_ENV);
    $m->migrate('up');
    $this->assertTrue(Database::table_exists($table_name), 'Table ['.$table_name.'] should exist after migrating up!');


    $this->assertTrue(Database::table_exists($table_name), 'Table ['.$table_name.'] should exist after creating it!');

    $c = new Customer();

    $c->firstname = 'john';
    $c->lastname = 'doe';

    $this->assertTrue($c->save(), 'Saving new customer record should be possible!');

    $customers = Customer::find('all');
    $this->assertEqual(count($customers), 1, 'Customer count should be [1] after saving one Customer!');

    $c = $customers[0];

    $this->assertEqual($c->id,        1,      'First customer id should be [1] but is ['.$c->id.']!');
    $this->assertEqual($c->firstname, 'john', 'First customer firstname should be [john] but is ['.$c->firstname.']!');
    $this->assertEqual($c->lastname,  'doe',  'First customer lastname should be [doe] but is ['.$c->lastname.']!');

    $c->firstname = 'perry';
    $c->lastname  = 'cox';

    $this->assertTrue($c->save(), 'Saving updated customer record should be possible!');

    $customers = Customer::find('all');
    $this->assertEqual(count($customers), 1, 'Customer count should be [1] after saving one Customer but is ['.count($customers).']!');

    $c = $customers[0];

    $this->assertEqual($c->id,        1,      'First customer id should be [1] but is ['.$c->id.']!');
    $this->assertEqual($c->firstname, 'perry', 'First customer firstname should be [perry] but is ['.$c->firstname.']!');
    $this->assertEqual($c->lastname,  'cox',  'First customer lastname should be [cox] but is ['.$c->lastname.']!');
  }
}
?>