<?php
$this->breadcrumbs=array(
	'Djjobs'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List DJJob', 'url'=>array('index')),
	array('label'=>'Create DJJob', 'url'=>array('create')),
	array('label'=>'Update DJJob', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete DJJob', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage DJJob', 'url'=>array('admin')),
);
?>

<h1>View DJJob #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'handler',
		'queue',
		'attempts',
		'run_at',
		'locked_at',
		'locked_by',
		'failed_at',
		'error',
		'created_at',
	),
)); ?>
