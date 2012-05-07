<?php
  $this->breadcrumbs = array(
      "Import data" => $this->createUrl("importsitehistory/site"),
      "Import History" => $this->createUrl("importsitehistory/index?siteconfig_id={$importHistory->siteconfig_id}"),
      "Rejected records"
  )
?>

<h1 class="action-title round" > Reject conditions </h1>  

<?php if(count($rejectPatients)): ?>
<div class="tableWrapper round">
    <table class="tgrid">
      <thead>
        <tr>
          <th> No </th>
          <th > Clinic ID </th>
          <th width="90"> Patient type </th>
          <th > Message </th>
          <th width="90"> Record error </th>
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
          <td> <?php echo $rejectPatient->patientType();  ?> </td>
          <td> <?php echo $errorMessage; ?>   </td>
          <td> 
            <?php $readId = "record-{$rejectPatient->id}" ;  ?>
            <div style="display:none;" >  
              <div id="<?php echo $readId; ?>" style="max-height: 500px;" >
                
                <h2 class="title" > Error details </h2>
                <div> <?php echo $errorMessage; ?> </div> 
                  
                <h2 class="title" > Patient </h2>
                <div >
                      <?php 
                          $record = unserialize($rejectPatient->record);
                          DaViewHelper::recordDetails(array($record));
                      ?> 
                
                
                      <?php 
                        $errRecords = unserialize($rejectPatient->err_records);
                        //echo "<pre>".print_r($errRecords, 1)."</pre>" ;
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

