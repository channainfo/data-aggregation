<?php
  $this->breadcrumbs = array(
      "Backup" => $this->createUrl("backup/index", array("siteconfig_id" => $siteconfig->id)) ,
      "Create"

  );
?>
<h1 class="action-title round"> Restoration </h1>

<?php $this->renderPartial("//siteconfig/_detail", array("siteconfig" => $siteconfig )) ; ?>
<?php $this->renderPartial("_form", array("model" => $model)) ?>
