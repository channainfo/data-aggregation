<?php

$this->breadcrumbs = array('Users');

$this->menu = array(
	array('label'=>'Create User', 'url'=>array('create')),
	array('label'=>'Manage User', 'url'=>array('admin')),
);
?>

<h1>Users </h1>

<?php 
  $flashes = Yii::app()->user->getFlashes();
  if($flashes):
  foreach($flashes as $key => $value): ?>
  <div class=" round flash-<?php echo $key ?>" > <?php echo $value; ?> </div>
<?php 
  endforeach;
  endif;
?>
  
<div class="action-bar round"> 
  <div class="action-bar-left"> List of users </div>
  <div class="action-bar-right"> 
    <?php echo CHtml::link("New", "create", array("class" => "button round")); ?> 
  </div>
  <div class="clear"></div>
   
</div>
<?php //ChTool::debug($dataProvider->getData()); ?>
<?php if(count($dataProvider->getData())): ?>

<table style="width: 100%;">
  <tr>
    <th> Name </td>
    <th> Login </td>
    <th> Role</td>
    <th> Email</td>
  </tr>

  <?php foreach($dataProvider->getData() as $row): ?>
    <tr>
    <td> <?php echo $row->name ?> </td>
    <td> <?php echo $row->login ?> </td>
    <td> <?php echo $row->role ?> </td>
    <td> <?php echo $row->email ?> </td>
  </tr>
  <?php endforeach; ?>
</table>
  
  <?php endif; ?>
<div class="action-bar round"> 
  <div class="action-bar-right"><?php echo CHtml::link("New", "create", array("class" => "button round")); ?> </div>
  <div class="clear"></div>
</div>  
<?php 
$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
)); 
?>
