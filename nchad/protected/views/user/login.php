<?php
$this->pageTitle=Yii::app()->name . ' - Login';
?>
<div class="form round" id="login" >
  <h1 id="login-el"> <?php echo CHtml::image( Yii::app()->request->baseUrl."/images/user-identity.png", "Login", array("width"=> 128, "height" => 128)); ?></h1>
  <p>Please fill out the following form with your login credentials:</p>
  
 <?php $form=$this->beginWidget('CActiveForm', array(
      'id'=>'login-form',
      'enableClientValidation'=>true,
      'clientOptions'=>array('validateOnSubmit'=>true	),
    )); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<div class="row">
		<?php echo $form->labelEx($model,'login'); ?>
		<?php echo $form->textField($model,'login', array("class"=>"login-input")); ?>
		<?php echo $form->error($model,'login'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password', array("class"=>"login-input")); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row buttons">
    <label>&nbsp;</label>
		<?php echo CHtml::submitButton('Login'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
