<?php
  $this->breadcrumbs = array(
  "User" => $this->createUrl("user/"),
  "Change password"
  );
  $form = $this->beginWidget("CActiveForm", array("id" => "PasswordChangeForm"));
?>
<div class="form round" >
  <h2> Fill in the following fields </h2>
  <p class="note">Fields with <span class="required">*</span> are required.</p>
  
  <div class="row" >
    <?php echo $form->labelEx($model, "old_password"); ?>
    <?php echo $form->passwordField($model, "old_password", array("class"=>"login-input", "autocomplete" => "off")) ; ?>
    <?php echo $form->error($model, "old_password") ; ?>
  </div>
  
  <div class="row" >
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
    <?php echo CHtml::submitButton("Change"); ?>
  </div>
  
</div>
<?php  $this->endWidget(); ?>