<|?php
class <?= $controller_class ?>Controller extends ApplicationController {
  public function index() {
    $this->redirect_to('controller:<?= $us_controller_class ?>', 'action:enlist');
  }
  
  public function enlist() {
    $this-><?= $pl_model_class ?> = <?= $model_class ?>::all();
  }
  
  public function add() {}
  
  public function create() {
    $<?= $us_model_class ?> = new <?= $model_class ?>($this->request()->params());
    if($<?= $us_model_class ?>->save()) {
      $this->flash->notice("Saved new <?= $model_class ?>");
      $this->redirect_to('controller:<?= $us_controller_class ?>', 'action:enlist');
    }
  }
  public function view() {
    $this-><?= $us_model_class ?> = <?= $model_class ?>::find_by_id($this->request()->params('id'));
  }
  
  public function edit() {
    $this-><?= $us_model_class ?> = <?= $model_class ?>::find($this->request()->params('id'));
  }
  public function update() {
    $<?= $us_model_class ?> = <?= $model_class ?>::find($this->request()->params('id'));
    $<?= $us_model_class ?>->set_attributes($this->request()->params());
    if($<?= $us_model_class ?>->save()) {
      $this->flash->notice("Updated <?= $model_class ?> {$<?= $us_model_class ?>->id}");
      $this->redirect_to('controller:<?= $us_controller_class ?>', 'action:enlist');
    }
  }
  
  public function delete() {
    if(!$<?= $us_model_class ?> = <?= $model_class ?>::find_by_id($this->request()->params('id'))) {
      $this->flash->notice("Could not find <?= $model_class ?> with ID {$this->request()->params('id')}");
      $this->redirect_to('controller:<?= $us_controller_class ?>', 'action:enlist');
    }
    
    if($<?= $us_model_class ?>->delete()) {
      $this->flash->notice("Deleted <?= $model_class ?> {$u->id}");
      $this->redirect_to('controller:<?= $us_controller_class ?>', 'action:enlist');
    } else {
      $this->flash()->notice("Could not delete <?= $model_class ?> {$<?= $us_model_class ?>->id}");
      $this->redirect_to('controller:<?= $us_controller_class ?>', 'action:enlist');
    }  
  }  
}