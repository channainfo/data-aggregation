<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $this->renderPartial("//shared/_requireField"); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'login'); ?>
		<?php echo $form->textField($model,'login',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'login'); ?>
	</div>

  <div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
  
	<div class="row">
		<?php echo $form->labelEx($model,"group_id" ); ?>
    <?php echo $form->dropDownList($model,'group_id', CHtml::listData(Group::model()->findAll(), 'id', 'name'), array('empty'=>'Select group')); ?>
		<?php echo $form->error($model,'group_id'); ?>
	</div>
  

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>60,'maxlength'=>255, 'autocomplete' => "off")); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

  <div class="row">
    <?php echo $form->labelEx($model, 'password_repeat'); ?>
    <?php echo $form->passwordField($model,"password_repeat", array("size"=>60,"maxlength" => 255, 'autocomplete' => "off")); ?>
    <?php echo $form->error($model, "password_repeat"); ?>
  </div>
  
	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>
  
  <div class="row">
		<?php echo $form->labelEx($model,'active'); ?>
		<?php echo $form->listBox($model,'active',User::$STATUS, array('size'=>1, "class" => "list-box" ) ); ?>
		<?php echo $form->error($model,'active'); ?>
	</div>
  

	<div class="row buttons">
    <label>&nbsp;</label>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array("class"=>"save-btn")); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->