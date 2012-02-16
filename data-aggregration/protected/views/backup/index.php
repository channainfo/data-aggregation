<?php
  $this->breadcrumbs = array(
      "Sites" => $this->createUrl("siteconfig/index"),
      "Restorations"
  )
?>
<?php echo DaViewHelper::titleActionGroup("Backup list", CHtml::link("New", $this->createUrl("backup/create", array("siteconfig_id" => $siteconfig->id)), array("class" => "btn-action-new round"))) ?>
<?php $this->renderPartial("//siteconfig/_detail", array("siteconfig" => $siteconfig)) ?>
<div class="tableWrapper round">
  <?php if(count($backups)): ?>
  <table class="tgrid">
    <thead>
      <tr>
        <th width="120"> Date </th>
        <th> Name </th>
        <th width="50"> Status </th>
        
      </tr>
    </thead>
  <?php 
  $i =0 ;
  foreach ($backups as $backup): ?>
      <?php $status = $backup->getStatusText();  ?>
      <tr class="<?php echo $i%2 == 0 ? "even" : "add" ?>" >
        <td> <?php echo $backup->created_at; ?> </td>
        <td> <?php echo basename($backup->filename); ?> </td>
        <td><span class="state <?php echo $status  ?>" ><?php echo ucfirst($status) ?></span></td>
      </tr>
  <?php 
  $i++;
  endforeach; ?>
  </table>     
  <?php endif; ?>
</div>

