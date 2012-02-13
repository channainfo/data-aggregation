<h1 class="action-title round"> Create site configuration </h1>
<?php
  $this->breadcrumbs = array("create" );
?>
<div class="form">
<?php $form = $this->beginWidget("CActiveForm", array("id" => "form")); ?>
  <div class="row">
    <?php echo $form->labelEx($model, "code");  ?>
    <?php echo $form->textField($model, "code");  ?>
    <?php echo $form->error($model, "code");  ?>
  </div>
  <div class="row">
    <?php echo $form->labelEx($model, "code");  ?>
    <?php echo $form->textField($model, "code");  ?>
    <?php echo $form->error($model, "code");  ?>
  </div>
  <div class="row">
    <?php echo $form->labelEx($model, "code");  ?>
    <?php echo $form->textField($model, "code");  ?>
    <?php echo $form->error($model, "code");  ?>
  </div>
  <div class="row">
    <?php echo $form->labelEx($model, "code");  ?>
    <?php echo $form->textField($model, "code");  ?>
    <?php echo $form->error($model, "code");  ?>
  </div>
  <div class="row">
    <?php echo $form->labelEx($model, "code");  ?>
    <?php echo $form->textField($model, "code");  ?>
    <?php echo $form->error($model, "code");  ?>
  </div>
  <div class="row">
    <?php echo $form->labelEx($model, "code");  ?>
    <?php echo $form->textField($model, "code");  ?>
    <?php echo $form->error($model, "code");  ?>
  </div>
  
  
  

  <div class="row">
    <label></label>
    <?php echo CHtml::submitButton("Save"); ?>
  </div> 
<?php $this->endWidget(); ?>
</div>

