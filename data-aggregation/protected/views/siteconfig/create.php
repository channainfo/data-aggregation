<h1 class="action-title round"> Database Configuration </h1>
<?php
  $this->breadcrumbs = array(
      "Databases" => $this->createUrl("siteconfig/index"),
      "create" 
      );
?>
<?php $this->renderPartial("_form", array("model" => $model));

