<?php
  $this->breadcrumbs = array(
      "Backup" => "",
      "" => ""
  );
?>
<h1 class="action-title round"> Restoration </h1>

<?php $this->renderPartial("//siteconfig/_detail", array("siteconfig" => $siteconfig )) ; ?>

<div class="form">
  
  <?php echo CHtml::form("", "post", array('enctype'=>'multipart/form-data')); ?>
  <div class ="row" >
    <?php echo CHtml::label("Backup file", "Backup_filename") ?>
    <?php echo CHtml::activeFileField($model, "filename", array("size" => 60)) ?>
    <?php echo CHtml::error($model, "filename"); ?>
  </div>
  
  <div class="row">
    <label></label>
    <?php echo CHtml::submitButton("Save", array("class" => "btn-save")); ?>
  </div>
  <?php echo CHtml::endForm(); ?>
</div>
