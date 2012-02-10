<h1 class="action-title round"> Create Group</h1>
<?php 
 $this->breadcrumbs=array(
	'Groups'=>array('index'),
	'Create',
);
?>
<?php echo $this->renderPartial("_form", array("model" => $model)); ?>
