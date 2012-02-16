<?php
$this->breadcrumbs = array('Sites');
?>
<?php echo DaViewHelper::titleActionGroup("Site List", CHtml::link("New", $this->createUrl("siteconfig/create"), array("class" => "btn-action-new round"))) ?>

<?php if(count($sites)): ?>
<div class="tableWrapper round">
  <table class="tgrid" >
    <thead>
      <tr>
        <th> Site </th>
        <th> Site name </th>
        <th> Db Server </th>
        <th> Db name </th>
        <th> Db user </th>
        <th> Action </th>
      </tr>
    </thead>
  <?php 
  $i =0 ;
   foreach($sites as $row): ?>
    <tr class="<?php echo $i%2?"even":"odd" ?>">
      <td> <?php echo CHtml::link($row->code, $this->createUrl("siteconfig/update/{$row->id}"), array("class" => "btn-link underline")); ?>  </td>
      <td> <?php echo $row->name ; ?>  </td>
      <td> <?php echo $row->host; ?>  </td>
      <td> <?php echo $row->db; ?>  </td>
      <td> <?php echo $row->user; ?>  </td>
      <td> 
        <?php echo CHtml::link("Backup", $this->createUrl("backup/create/", array("siteconfig_id"=>"{$row->id}")), array("class" => "btn-action round") ) ?> 
        <?php echo CHtml::link("Edit", $this->createUrl("siteconfig/update/{$row->id}"), array("class" => "btn-action-edit round") ) ?> 
        <?php echo CHtml::link("Delete",$this->createUrl( "siteconfig/delete/{$row->id}"), array("class" => "btn-action-delete round delete") ) ?> 
      </td>
    </tr>
  <?php $i++; endforeach; ?>
  </table>
  
  <br />
  <div class="right-align">
    <?php $this->widget("CLinkPager", array("pages" => $pages)) ; ?>
  </div>  
  <div class="clear"></div>
  <br />
</div>
<?php endif; ?>