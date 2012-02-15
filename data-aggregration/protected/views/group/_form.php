<?php
 $form = $this->beginWidget("CActiveForm", array("id" => "active-form")); 
?>
<div class="form" >
  <?php $this->renderPartial("//shared/_requireField"); ?>
  <div class="row"> 
    <?php echo $form->labelEx($model, "name"); ?>
    <?php echo $form->textField($model, "name", array("size" => 60, "maxlength" => 255)); ?>
    <?php echo $form->error($model, "name"); ?>
    
  </div>
  
  <div class="row"> 
    <?php echo $form->labelEx($model, "description"); ?>
    <?php echo $form->textarea($model, "description", array("rows" => 8, "cols" => 55)); ?>
    <?php echo $form->error($model, "description"); ?>
  </div>
  
  <div class="row">
    <label></label>
    <?php echo CHtml::submitButton("Save"); ?>
  </div>
  
</div>

<?php $this->endWidget(); ?>