<?php
  $this->breadcrumbs = array(
      "Import data" => $this->createUrl("importsitehistory/site"),
      "Import History" => $this->createUrl("importsitehistory/index?siteconfig_id={$importHistory->siteconfig_id}"),
      "Rejected records"
  )
?>

<h1 class="action-title round" > Reject conditions </h1>  

<?php if(count($rejectConditions)): ?>
<div class="tableWrapper round">
    <table class="tgrid">
      <thead>
        <tr>
          <th width="120"> Table </th>
          <th width="50"> Code </th>
          <th > Message </th>
          <th width="90"> Record error </th>
        </tr>
      </thead>
    <?php 
    $i =0 ;
    foreach ($rejectConditions as $rejectCondition): 
      $errorMessage = DaViewHelper::htmlControlErrorOutput($rejectCondition->message);
      ?>
        <tr class="<?php echo $i%2 == 0 ? "even" : "add" ?>" >
          <td> <?php echo $rejectCondition->tableName;  ?> </td>
          <td> <?php echo $rejectCondition->code; ?> </td>
          <td> <?php echo $errorMessage;  ?> </td>
          <td> 
            <?php $readId = "record-{$rejectCondition->id}" ;  ?>
            <div style="display:none;">  
              <div style="width: 500px;" id="<?php echo $readId; ?>" >
                <?php 
                    $record = unserialize($rejectCondition->record);
                    DaViewHelper::outputVars($record);
                ?> 
              </div>
            </div>  
            <a href="#<?php echo $readId; ?>" class="btn-thirdparty round readmore" title="<?php echo htmlentities($errorMessage) ?>" > More detail </a>
          </td>
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
  <script>
    jQuery(function(){
      
      $(".readmore").fancybox({
            'titlePosition'		: 'inside',
            'transitionIn'		: 'none',
            'transitionOut'		: 'none',
            "width"           : 500 ,
            "height"           : 400
       });
        
    });
  </script>
<?php endif; ?>

