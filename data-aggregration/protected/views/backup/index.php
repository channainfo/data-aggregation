<?php
  $this->breadcrumbs = array(
      "Sites" => $this->createUrl("siteconfig/index"),
      ""
  )
?>
<h1 class="action-title round">Restoration</h1>
<?php $this->renderPartial("//siteconfig/_detail", array("siteconfig" => $siteconfig)) ?>
