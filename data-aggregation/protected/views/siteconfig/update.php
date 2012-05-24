<?php
$this->breadcrumbs=array(
	'Sites'=>array('siteconfig/index'),
	$model->name
);
?>

<h1 class="action-title round">Update Site </h1>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
