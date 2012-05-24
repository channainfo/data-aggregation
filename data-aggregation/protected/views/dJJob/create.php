<?php
$this->breadcrumbs=array(
	'Djjobs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List DJJob', 'url'=>array('index')),
	array('label'=>'Manage DJJob', 'url'=>array('admin')),
);
?>

<h1>Create DJJob</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>