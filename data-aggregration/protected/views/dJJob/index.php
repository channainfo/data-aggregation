<?php
$this->breadcrumbs=array(
	'Djjobs',
);

$this->menu=array(
	array('label'=>'Create DJJob', 'url'=>array('create')),
	array('label'=>'Manage DJJob', 'url'=>array('admin')),
);
?>

<h1>Djjobs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
