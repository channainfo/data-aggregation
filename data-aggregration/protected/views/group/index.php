<?php
  $this->breadcrumbs = array("Group");
  echo DaViewHelper::titleActionGroup("Group List", CHtml::link("New", "create", array("class"=>"btn-action-new round"))) ?>
<?php
  if(count($rows)):
  $i = 0 ;  
?>
<div class="tableWrapper round">
  <table class="tgrid" >
   <thead> 
    <tr>
      <th width="100" >Name</th>
      <th>Description</th>
    </tr>
   </thead> 
  
<?php foreach($rows as $row) : ?>
    <tr class="<?php echo $i%2 ? "odd": "even" ?>" >
      <td><?php echo CHtml::encode($row->name) ?> </td>
      <td><?php echo DaUtilString::truncate(CHtml::encode($row->description)) ; ?> </td>
    </tr>
<?php $i++; endforeach; ?>
  </table>
</div>  
<?php endif; ?>

