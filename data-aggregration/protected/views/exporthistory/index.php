<?php
/**
 * @param DaModelStatus $row 
 */
$this->breadcrumbs = array('Sites');
?>
<?php echo DaViewHelper::titleActionGroup("Export History List", CHtml::link("New", $this->createUrl("exporthistory/create"), array("class" => "btn-action-new round"))) ?>

<?php if(count($rows)): ?>
<div class="tableWrapper round">
  <table class="tgrid" >
    <thead>
      <tr>
        <th width="30"  >  # </th>
        <th> Date Start </th>
        <th> Date End </th>
        <th> Reversable </th>
        <th> Site </th>
        <th> Status </th>
        <th> File </th>
        <th width="100" > Action </th>
      </tr>
    </thead>
  <?php 
  $i =0 ;
   foreach($rows as $row): ?>
    <tr class="<?php echo $i%2?"even":"odd" ?>">
      <td> <?php echo CHtml::link("{$row->id}",$this->createUrl( "exporthistory/view/{$row->id}"), array() ) ?> </td>
      <td> <?php echo $row->date_start; ?>  </td>
      <td> <?php echo $row->date_end; ?>  </td>
      <td> <?php echo $row->getReversableText(); ?>  </td>
      <td> <?php echo $row->site_text; ?>  </td>
      <td> <?php echo "<span class='state {$row->getStatusText()}-state'>". ucfirst($row->getStatusText()). "</span>" ; ?>  </td>
      <td> <?php echo $row->file ; ?>  </td>
      <td> 
        <?php echo CHtml::link("Delete",$this->createUrl( "exporthistory/delete/{$row->id}"), array("class" => "btn-action-delete round delete") ) ?> 
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