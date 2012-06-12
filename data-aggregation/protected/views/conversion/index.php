<?php
/**
 * @param DaModelStatus $row 
 */
$this->breadcrumbs = array('Conversion');
?>
<?php echo DaViewHelper::titleActionGroup("Conversion List", CHtml::link("New", $this->createUrl("conversion/create"), array("class" => "btn-action-new round"))) ?>

<?php if(count($rows)): ?>
<div class="tableWrapper round">
  <table class="tgrid" >
    <thead>
      <tr>
        <th width="30"  >  # </th>
        <th> Date Start </th>
        <th> Date End </th>
        <th> Status </th>
        <th> Source </th>
        <th> Result </th>
        <th> Error </th>
        <th width="100" > Action </th>
      </tr>
    </thead>
  <?php 
  $i =0 ;
   foreach($rows as $row): ?>
    <tr class="<?php echo $i%2?"even":"odd" ?>">
      <td> <?php echo $i+1 + $pages->getOffset();  ?> </td>
      <td> <?php echo $row->date_start; ?>  </td>
      <td> <?php echo $row->date_end; ?>  </td>
      <td> <?php echo "<span class='state {$row->getStatusText()}-state'>". ucfirst($row->getStatusText()). "</span>" ; ?>  </td>
      <td> <?php echo ($row->src)? CHtml::link($row->src, $this->createUrl("conversion/src/{$row->id}")): "" ; ?>  </td>
      <td> <?php echo ($row->des)? CHtml::link($row->des, $this->createUrl("conversion/des/{$row->id}")): "" ; ?>  </td>
      <td> <?php echo $row->message; ?> </td>
      <td> 
        <?php if(Yii::app()->user->isAdmin()): ?>
        <?php echo CHtml::link("Delete",$this->createUrl( "conversion/delete/{$row->id}"), array("class" => "btn-action-delete round delete", "data-tip" => "Are you sure delete this conversion ?") ) ?> 
        <?php endif ;?>
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