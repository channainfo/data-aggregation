<?php
  $this->breadcrumbs = array(
      "Import data" => $this->createUrl("importsitehistory/site"),
      "Import History" => $this->createUrl("importsitehistory/index?siteconfig_id={$importHistory->siteconfig_id}"),
      "Rejected records"
  );
      
 if($importHistory->attributes["status"]== ImportSiteHistory::START || $importHistory->attributes["status"]== ImportSiteHistory::PENDING): ?>
<?php echo DaViewHelper::titleActionGroupAll("Reject conditions", "<span class='disabled round' >Import still in progress</span>") ?>
<?php else: ?>
<?php echo DaViewHelper::titleActionGroupAll("Reject conditions", CHtml::link("Download as CSV", $this->createUrl("rejectpatient/export", array("import_site_history_id" => $importHistory->id)), array("class" => "btn-thirdparty round"))) ?>
<?php endif; ?>
<?php if(count($rejectPatients)): ?>
<div class="tableWrapper round">
    <table class="tgrid">
      <thead>
        <tr>
          <th> No </th>
          <th > Clinic ID </th>
          <th width="90"> Patient type </th>
          <th > Message </th>
          <th> Table </th>
          <th width="90"> More detail </th>
        </tr>
      </thead>
    <?php 
    $i =0 ;
    foreach ($rejectPatients as $rejectPatient): 
      $errorMessage = "";
      if($rejectPatient->message){
          $errs = unserialize ($rejectPatient->message);
          $errorMessage = DaViewHelper::outputMessagePatient($errs,1);
      }

      
      ?>
        <tr class="<?php echo $i%2 == 0 ? "even" : "add" ?>" >
          <td> <?php echo 1+$i+$pages->getOffset(); ?> </td>
          <td> 
            <?php
                $record = $rejectPatient->patientRecord() ; 
                if(count($record))
                   echo DaRecordReader::getIdFromRecord($rejectPatient->tableName, $record);
                
            ?> 
          </td>
          <td> <?php echo RejectPatient::patientType($rejectPatient->tableName);  ?> </td>
          <td> <?php echo $errorMessage; ?>   </td>
          <td> 
             <?php
                $record = unserialize($rejectPatient->record);
                $errRecords = unserialize($rejectPatient->err_records);
                
                foreach($errRecords as $errTable => $tmp){
                  echo $errTable;
                  break;
                }
             ?>
          </td>
          <td> 
            <?php $readId = "record-{$rejectPatient->id}" ;  ?>
            <div style="display:none;" >  
              <div id="<?php echo $readId; ?>" style="max-height: 500px;" >
                
                <h2 class="title" > Error details </h2>
                <div> <?php echo $errorMessage; ?> </div> 
                  
                <h2 class="title" > Patient </h2>
                <div >
                  <?php 
                      DaViewHelper::recordDetails(array($record));
                      DaViewHelper::outputTraceRecords($errRecords);
                  ?>
                </div>
              </div>
            </div>  
            <a href="#<?php echo $readId; ?>" class="btn-thirdparty round readmore" > More detail </a>
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

