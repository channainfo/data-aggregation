<?php
$this->breadcrumbs=array(
	'Databases'=>array('siteconfig/index'),
	$model->name
);
?>
<h1 class="action-title round">Update Database Configuration </h1>
<?php if(Yii::app()->user->isAdmin()): ?>
  <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
<?php else :?>
  <?php echo $this->renderPartial('_detail', array('siteconfig'=>$model)); ?>
<?php endif; ?>

