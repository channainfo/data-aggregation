<?php

$this->breadcrumbs = array('Users');

$this->menu = array(
	array('label'=>'Create User', 'url'=>array('create')),
	array('label'=>'Manage User', 'url'=>array('admin')),
);
?>

<div class="action-title round"> 
  <div class="action-bar-left"> List of users </div>
  <div class="action-bar-right"> 
    <?php echo CHtml::link("New", "create", array("class" => "btn-action round")); ?> 
  </div>
  <div class="clear"></div>
</div>
<?php 
$this->widget('zii.widgets.grid.CGridView', array(
  'id' => "grid-user",  
	'dataProvider'=>$dataProvider,
  //'columns' => array("login", "name", "role"),
    
    
)); 
?>
