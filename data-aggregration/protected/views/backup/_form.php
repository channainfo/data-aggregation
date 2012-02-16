<?php echo CHtml::errorSummary($model); ?>
<div class="form">
  <?php echo CHtml::form("", "post", array('enctype'=>'multipart/form-data')); ?>
  <div class ="row" >
    <?php echo CHtml::activeLabel($model, "filename") ?>
    <?php echo CHtml::activeFileField($model, "filename", array("size" => 60)) ?>
    <?php echo CHtml::error($model, "filename"); ?>
  </div>
  
  <div class="row">
    <label></label>
    <?php echo CHtml::submitButton("Save", array("class" => "btn-save")); ?>
  </div>
  <?php echo CHtml::endForm(); ?>
</div>