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
          <th width="120"> Date </th>
          <th width="50"> Status </th>
          <th> Reason </th>
          <th width="120"> Inserted </th>
          <th width="120"> Rejected </th>
          <th width="120"> Total </th>
          
          
          <th width="120"> Action </th>
        </tr>
      </thead>
    <?php 
    $i =0 ;
    foreach ($importHistories as $importHistory): ?>
        <?php 
          $status = $importHistory->getStatusText();  
          $reasonId = "reason{$importHistory->id}";
        ?>
        <?php
          $cls ="";
          if($importHistories[0]->restorable() && $i == 0)
            $cls = "restoring";
        ?>
        <tr class="<?php echo $i%2 == 0 ? "even" : "add" ?>" >
          <td> <?php echo date("Y-m-d", strtotime($importHistory->created_at) ); ?> </td>
          <td> <span class="state <?php echo "{$status}-state"  ?> <?php echo $cls; ?>" ><?php echo ucfirst($status) ?></span></td>
          <td> 
            
            <div  >
               <?php
                  $maxLength = 150 ;
                  if(strlen($importHistory->reason)> $maxLength ): ?>
                  <p> 
                    <?php echo  htmlspecialchars(substr($importHistory->reason, 0 , $maxLength)); ?> 
                    &nbsp;&nbsp;&nbsp;&nbsp; 
                    <span> <a href="#<?php echo $reasonId ?>" class="readmore_reason " > Show more </a> </span>  
                  </p>
                  <div style="display:none;">
                    <div id="<?php echo $reasonId ; ?>" > 
                      <p>  <?php echo htmlspecialchars($importHistory->reason); ?>  </p>  
                    </div>
                  </div>
                  <?php else: ?>
                  <p> <?php echo htmlspecialchars($importHistory->reason); ?> </p>
                  <?php endif; ?>
            </div>  
          
          </td>
          <td> 
             <?php 
                $info = $importHistory->getInfo();  
                if(isset($info["inserted"])){
                    foreach($info["inserted"] as $tableName => $total){
                      echo " <div> {$tableName}: {$total} </div> " ;
                    }
                }
             ?>
          </td>
          <td>
              <?php
                  if(isset($info["rejected"])){
                    foreach($info["rejected"] as $tableName => $total){
                      echo " <div> {$tableName}: {$total} </div> " ;
                    }
                }
              ?> 
          </td>
          <td> 
            <?php
                  if(isset($info["total"])){
                    foreach($info["total"] as $tableName => $total){
                      echo " <div> {$tableName}: {$total} </div> " ;
                    }
                }
              ?>
          </td>
          <td> 
            <?php echo CHtml::link("Delete", $this->createUrl("importsitehistory/delete/{$importHistory->id}"), array("class" => "btn-action-delete delete round ") ) ?>
            <?php echo CHtml::link("Rejects" , $this->createUrl("rejectpatient/index", array("import_site_history_id" => $importHistory->id)), array("class" => "btn-action round ")); ?> 
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
    <script>
    jQuery(function(){
      
      $(".readmore_reason").fancybox({
            'titlePosition'		: 'inside',
            'transitionIn'		: 'none',
            'transitionOut'		: 'none',
            "width"           : 500 ,
            "height"           : 400
       });
        
    });
  </script>
  </div>
<?php endif; ?>

