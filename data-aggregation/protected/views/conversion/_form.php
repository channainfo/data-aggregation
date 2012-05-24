<?php echo CHtml::errorSummary($model); ?>
<div class="form">
  <?php echo CHtml::form("", "post", array('enctype'=>'multipart/form-data')); ?>
  
  <div class ="row" >
    <?php echo CHtml::activeLabel($model, "file") ?>
    <?php echo CHtml::activeFileField($model, "src", array("size" => 60)) ?>
    <?php echo CHtml::error($model, "src"); ?>
  </div>
  
  
  
  
  
  
  <div class="row">
    <label></label>
    <?php echo CHtml::submitButton("Start conversion", array("class" => "btn-save")); ?>
  </div>
  <?php echo CHtml::endForm(); ?>
</div>