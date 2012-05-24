<?php

$this->breadcrumbs = array('Users');

$this->menu = array(
	array('label'=>'Create User', 'url'=>array('create')),
	array('label'=>'Manage User', 'url'=>array('admin')),
);
?>
<?php echo DaViewHelper::titleActionGroup("List of users", CHtml::link("New", $this->createUrl("user/create"), array("class" => "btn-action-new round"))) ?>

<?php if(count($users)): ?>
<div class="tableWrapper round">
  <table class="tgrid" >
    <thead>
      <tr>
        <th> Login </th>
        <th> Name </th>
        <th> Group </th>
        <th> Active </th>
        <th> Email </th>
        <th> Action </th>
        
      </tr>
    </thead>
  <?php 
  $i =0 ;
   foreach($users as $row): ?>
    <?php $class ?> 
    <tr class="<?php echo $i%2?"even":"odd" ?>">
      <td> <?php echo $row->login; ?>  </td>
      <td> <?php echo $row->name ; ?>  </td>
      <td> <?php echo $row->group->name; ?>  </td>
      <td> <?php echo $row->getActive(); ?>  </td>
      <td> <?php echo $row->email; ?>  </td>
      <td> 
        <?php echo CHtml::link("Edit", $this->createUrl("user/update/{$row->id}"), array("class" => "btn-action-edit round") ) ?> 
        <?php 
          if(Yii::app()->user->id != $row->id)
            echo CHtml::link("Delete", $this->createUrl("user/delete/{$row->id}"), array("class" => "btn-action-delete delete round ") ) 
         ?> 
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