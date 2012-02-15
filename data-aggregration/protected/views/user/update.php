<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
  $model->name  
	//$model->name=>array('view','id'=>$model->id),
	//'Update',
);
?>
<h1 class="action-title round">Update User </h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>