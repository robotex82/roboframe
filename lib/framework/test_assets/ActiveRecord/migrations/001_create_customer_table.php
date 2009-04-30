<?php
  class CreateCustomerTable extends Migration {
    public function up() {
      $this->create_table('tbl_customers'
                        , 'firstname:string:32'
                        , 'lastname:string:32'
                        , 'created_at:datetime'
                        , 'updated_at:datetime'
      );
    }
    
    public function down() {
      $this->drop_table('tbl_customers');
    }
  }
?>