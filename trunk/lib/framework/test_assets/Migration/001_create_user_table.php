<?php
  class CreateUserTable extends Migration {
    public function up() {
      $this->create_table('tbl_users'
                        , 'login:string:32'
                        , 'client:integer'
                        , 'created_at:datetime'
                        , 'updated_at:datetime'
      );
    }
    
    public function down() {
      $this->drop_table('tbl_users');
    }
  }
?>