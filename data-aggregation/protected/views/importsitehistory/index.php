<?php
  $this->breadcrumbs = array(
      "Import data" => $this->createUrl("importsitehistory/site"),
      "Import History" ) ;
?>
<?php
  if($siteconfig)
    $this->renderPartial("//siteconfig/_detail", array("siteconfig" => $siteconfig))
?>
<?php if(count($importHistories)): ?>
<div class="tableWrapper round">
    <?php 
      $firstImport = $importHistories[0];
      if($firstImport->status == ImportSiteHistory::PENDING): ?>
        <style type="text/css" >
          .ui-progressbar { position:relative; width: 350px; }
          .pblabel { position: absolute; display: block; width: 100%; text-align: center; line-height: 1.2em;font-weight:normal; }
          .ui-progressbar-value{ overflow: hidden; }
          .ui-progressbar-value .pblabel { position: relative; font-weight: normal; color:red; }
        </style>
  
      <div>
          <div id="progressbar" style="width:300px; float: left;margin-right: 10px;" >
            <span class="pblabel">
              Importing : <span id="importTable"  >  </span> 
              <span id="importPercentage" > </span> 
            </span>
          </div>
          <div class="importLabel" > 
            <div class="importTableValue"  id="importCurrent"  > 0 </div>
            <div class="importTableValue label" >of</div>
            <div class="importTableValue"  id="importTotal"   >
          </div>
      </div>
    
    <div class="clear">&nbsp;</div>  
      <script type="text/javascript">
        function updateBar(){
          $.ajax({
            url: "<?php echo Yii::app()->createUrl("importsitehistory/progress", array("importId" => $firstImport->id )) ?>",
            cache: false,
            dataType: "json",
            success: function(response){
              if(response["finished"] == true){
                window.location.reload();   
              }
              else{
                updateProgressBar(response);
                setTimeout(updateBar, 500);
              }
            }
          });
        }
        function updateProgressBar(response){
          $("#progressbar").progressbar("value", response["percentage"] );
          $("#importPercentage").html(response["percentage"]+"%");
          $("#importTotal").html( response["total_record"]);
          $("#importCurrent").html( response["current_record"]);
          $("#importTable").html( response["importing_table"]);
        }
        function createProgressbar(){
          $("#progressbar").progressbar({
              value: 0 ,
              change: function(event, ui) {    
                var newVal = $(this).progressbar('option', 'value');
                var color = "#00b" ;
                if(newVal<30)
                   color = "#00f" ;
                else if(newVal<60)
                  color = "#0f0" ;
                else if(newVal < 80)
                  color = "#E38A39";
                else
                  color = "#f00";
                $('.pblabel').css("color", color);
              }
            }
          );
        }
        $(function(){
          createProgressbar();
          updateBar();
        }); 
      </script>
    <?php else: ?>
      <script type="text/javascript">
        function reloadUpdate(){
          window.location.reload();
        }
        $(function(){
            setTimeout(reloadUpdate, 5000);
        });
      </script>
    <?php endif; ?>

    <table class="tgrid">
      <thead>
        <tr>
          <th width="150"> Date </th>
          <th width="50"> Status </th>
          <th width="150"> Site </th>
          <th> Reason </th>
          <th width="120"> Inserted </th>
          <th width="120"> Rejected </th>
          <th width="120"> Total </th>
          <th width="180"> Action </th>
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
          <td> <?php echo date("Y-m-d, H:i:s", strtotime($importHistory->created_at) ); ?> </td>
          <td> <span class="state <?php echo "{$status}-state"  ?> <?php echo $cls; ?>" ><?php echo ucfirst($status) ?></span></td>
          <td> <?php echo "{$importHistory->siteconfig->code} - {$importHistory->siteconfig->name} ";  ?></td>
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
            <?php if(Yii::app()->user->isAdmin()):?>
            <?php echo CHtml::link("Delete", $this->createUrl("importsitehistory/delete/{$importHistory->id}"), array("class" => "btn-action-delete delete round ", "data-tip" => "Are you sure to delete this import history ?") ) ?>
            <?php endif;?>
            <?php echo CHtml::link("Rejected Patients" , $this->createUrl("rejectpatient/index", array("import_site_history_id" => $importHistory->id)), array("class" => "btn-action round ")); ?> 
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

