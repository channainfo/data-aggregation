<?php
$this->breadcrumbs = array('Site');
?>
<?php echo DaViewHelper::titleActionGroup("Site List", CHtml::link("New", "create", array("class" => "btn-action round"))) ?>

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
    <?php $class ?> 
    <tr class="<?php echo $i%2?"even":"odd" ?>">
      <td> <?php echo $row->code; ?>  </td>
      <td> <?php echo $row->name ; ?>  </td>
      <td> <?php echo $row->host; ?>  </td>
      <td> <?php echo $row->db; ?>  </td>
      <td> <?php echo $row->user; ?>  </td>
      <td> 
        <?php echo CHtml::link("Edit", "update/{$row->id}", array("class" => "btn-action-table round") ) ?> 
        <?php echo CHtml::link("Delete", "delete/{$row->id}", array("class" => "btn-action-table round delete") ) ?> 
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