<?php
  $this->breadcrumbs = array(
      "Backups" => $this->createUrl("backup/index", array("siteconfig_id" => $siteconfig->id)) ,
      "Create"

  );
?>
<h1 class="action-title round"> Restoration </h1>

<?php $this->renderPartial("//siteconfig/_detail", array("siteconfig" => $siteconfig )) ; ?>
<?php if(isset($backup) && $backup->restorable()): ?>
  <div class="round restore"> The site is in the restoring state </div>
<?php else: ?>
  <?php $this->renderPartial("_form", array("model" => $model)) ?>
<?php endif; ?>


