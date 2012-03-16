<?php
  $this->breadcrumbs = array(
      "Import data" => $this->createUrl("importsitehistory/site"),
      "Import History"
  )
?>
<?php
  if($siteconfig)
    $this->renderPartial("//siteconfig/_detail", array("siteconfig" => $siteconfig))
?>

<?php if(count($importHistories)): ?>
<div class="tableWrapper round">
    <table class="tgrid">
      <thead>
        <tr>
          <th>No </th> 
          <th width="120"> Date </th>
          <th width="50"> Status </th>
          <th> Reason </th>
          <th> Delete </th>

        </tr>
      </thead>
    <?php 
    $i =0 ;
    foreach ($importHistories as $importHistory): ?>
        <?php $status = $importHistory->getStatusText();  ?>
        <?php
          $cls ="";
          if($importHistories[0]->restorable() && $i == 0)
            $cls = "restoring";
        ?>
        <tr class="<?php echo $i%2 == 0 ? "even" : "add" ?>" >
          <td> <?php echo $i+1; ?> </td>
          <td> <?php echo date("Y-m-d", strtotime($importHistory->created_at) ); ?> </td>
          <td> <span class="state <?php echo "{$status}-state"  ?> <?php echo $cls; ?>" ><?php echo ucfirst($status) ?></span></td>
          <td> <?php echo nl2br($importHistory->reason); ?></td>
          <td> <?php echo CHtml::link("Delete", $this->createUrl("importsitehistory/delete/{$importHistory->id}"), array("class" => "btn-action-delete delete round ") ) ?> </td>
        </tr>
    <?php 
    $i++;
    endforeach; ?>
    </table>     
    <br />
    <div class="right-align">
      <?php $this->widget("CLinkPager", array("pages" => $pages)) ; ?>
    </div>  
    <div class="clear"></div>
    <br />
  </div>
<?php endif; ?>

