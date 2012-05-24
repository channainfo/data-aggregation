<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'djjob-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'handler'); ?>
		<?php echo $form->textField($model,'handler',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'handler'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'queue'); ?>
		<?php echo $form->textField($model,'queue',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'queue'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'attempts'); ?>
		<?php echo $form->textField($model,'attempts'); ?>
		<?php echo $form->error($model,'attempts'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'run_at'); ?>
		<?php echo $form->textField($model,'run_at'); ?>
		<?php echo $form->error($model,'run_at'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'locked_at'); ?>
		<?php echo $form->textField($model,'locked_at'); ?>
		<?php echo $form->error($model,'locked_at'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'locked_by'); ?>
		<?php echo $form->textField($model,'locked_by',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'locked_by'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'failed_at'); ?>
		<?php echo $form->textField($model,'failed_at'); ?>
		<?php echo $form->error($model,'failed_at'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'error'); ?>
		<?php echo $form->textField($model,'error'); ?>
		<?php echo $form->error($model,'error'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'created_at'); ?>
		<?php echo $form->textField($model,'created_at'); ?>
		<?php echo $form->error($model,'created_at'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->