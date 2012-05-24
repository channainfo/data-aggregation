<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('handler')); ?>:</b>
	<?php echo CHtml::encode($data->handler); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('queue')); ?>:</b>
	<?php echo CHtml::encode($data->queue); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('attempts')); ?>:</b>
	<?php echo CHtml::encode($data->attempts); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('run_at')); ?>:</b>
	<?php echo CHtml::encode($data->run_at); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('locked_at')); ?>:</b>
	<?php echo CHtml::encode($data->locked_at); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('locked_by')); ?>:</b>
	<?php echo CHtml::encode($data->locked_by); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('failed_at')); ?>:</b>
	<?php echo CHtml::encode($data->failed_at); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('error')); ?>:</b>
	<?php echo CHtml::encode($data->error); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_at')); ?>:</b>
	<?php echo CHtml::encode($data->created_at); ?>
	<br />

	*/ ?>

</div>