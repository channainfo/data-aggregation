<?php
$this->breadcrumbs=array(
	'Djjobs'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List DJJob', 'url'=>array('index')),
	array('label'=>'Create DJJob', 'url'=>array('create')),
	array('label'=>'View DJJob', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage DJJob', 'url'=>array('admin')),
);
?>

<h1>Update DJJob <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>