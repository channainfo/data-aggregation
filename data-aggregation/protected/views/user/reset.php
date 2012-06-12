<?php
  $this->breadcrumbs = array(
  "User" => $this->createUrl("user/"),
  "Reset password"
  );
  $form = $this->beginWidget("CActiveForm", array("id" => "ResetForm"));
?>
<h1 class="action-title round"> Change Password </h1>
<div class="form round" >

  <?php echo $this->renderPartial("//shared/_requireField"); ?>
  <div class="row" >
    <?php echo $form->hiddenField($model, "user_id");?>
    <?php echo $form->labelEx($model, "password"); ?>
    <?php echo $form->passwordField($model, "password", array("class"=>"login-input", "autocomplete" => "off")) ; ?>
    <?php echo $form->error($model, "password") ; ?>
  </div>
  
  <div class="row" >
    <?php echo $form->labelEx($model, "password_repeat"); ?>
    <?php echo $form->passwordField($model, "password_repeat" , array("class"=>"login-input" , "autocomplete" => "off") ) ; ?>
    <?php echo $form->error($model, "password_repeat") ; ?>
  </div>
  
  <div class="row">
    <label>&nbsp;</label>
    <?php echo CHtml::submitButton("Reset", array("class" => "btn-save")); ?>
  </div>
  
</div>
<?php  $this->endWidget(); ?>