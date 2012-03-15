<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'handler'); ?>
		<?php echo $form->textField($model,'handler',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'queue'); ?>
		<?php echo $form->textField($model,'queue',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'attempts'); ?>
		<?php echo $form->textField($model,'attempts'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'run_at'); ?>
		<?php echo $form->textField($model,'run_at'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'locked_at'); ?>
		<?php echo $form->textField($model,'locked_at'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'locked_by'); ?>
		<?php echo $form->textField($model,'locked_by',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'failed_at'); ?>
		<?php echo $form->textField($model,'failed_at'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'error'); ?>
		<?php echo $form->textField($model,'error'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'created_at'); ?>
		<?php echo $form->textField($model,'created_at'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->