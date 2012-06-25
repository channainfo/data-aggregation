<?php
  $this->breadcrumbs = array(
      "Export" => $this->createUrl("exporthistory/index"),
      $model->getSiteText(" , ")
  );
 ?>

<h1 class="action-title round"> Export Detail : <span class='dacalender'> <?php echo $model->date_start ;?> </span> </h1>

<div >
  <table>
    <tr> 
      <td width="120"> Export type </td>
      <td> : <?php echo $model->getReversibleText(); ?> </td>
    </tr>
    <tr> 
      <td width="120"> Site </td>
      <td> : <?php echo  $model->getSiteText(" , "); ?> </td>
    </tr>
    <tr> 
      <td width="120"> Status </td>
      <td> :  <?php echo $model-> getStatusText(); ?> </td>
    </tr>
    <tr> 
      <td width="120"> File </td>
      <td> : <?php echo CHtml::link($model->file, $this->createUrl("exporthistory/dwl/{$model->id}")) ; ?> </td>
    </tr>
  </table>
  <p> &nbsp; </p>
  <div class="row" style="border:1px solid #ccc; padding: 10px;" >
    <?php      
     $allTables = ExportHistory::tableList();
     $tcount = count($allTables);
     $tcolumn = round($tcount/4);
     $ti = 0;
     
     $selectedTables = $model->getTableList();
     
     foreach($allTables as $tableName => $cols):
       if($ti == 0)
         echo "<div class='exportTableItem' >" ;
       elseif($ti%$tcolumn == 0)
         echo "</div><div class='exportTableItem' >" ;
       $content = $tableName;
       
       if(isset($selectedTables[$tableName]))
         $content = "<a href='#{$tableName}' ><b >{$tableName}</b> </a>" ;
    ?>
    <div >
      <?php echo CHtml::checkBox("{$tableName}", isset($selectedTables[$tableName]), array("disabled" =>"disabled"));  ?>
      <?php if(isset($selectedTables[$tableName])): ?>
          <a href="#<?php echo $tableName ?>_columns" class="fancy_table_list" > <?php echo $tableName; ?> </a>
      <?php else: ?>
          <span > <?php echo $content;  ?> </span>
      <?php endif;  ?>
    </div>
    <?php
     $ti++;
     if($ti == $tcount)
       echo "</div>" ;
     endforeach; 
   ?>
    <div class="clear">&nbsp;</div>
    
  </div>
  
  
  <?php foreach($allTables as $tableName => $cols): ?>
        <?php if(isset($selectedTables[$tableName])):?>
            <div style="display:none;">
                <div id="<?php echo $tableName ?>_columns" >
                  <h2 class="action-title round"> Table:  <?php echo $tableName; ?>  </h2>
                  <div class="row">
                    <?php foreach($cols as $col): ?>
                        <div class="exportTableItem"> 
                           <?php if(isset($selectedTables[$tableName][$col])): ?>
                              <?php echo CHtml::checkBox($col, true, array("disabled"=> "disabled")); ?>
                              <span> <b> <?php echo $col; ?> </b> </span>  
                           <?php else :?>
                              <?php echo CHtml::checkBox($col, false, array("disabled"=> "disabled")); ?>
                              <span><?php echo $col; ?> </span> 
                           <?php endif ;?>
                        </div>
                    <?php endforeach; ?>
                  </div>
                </div>
            </div>
        <?php endif; ?>      
  <?php endforeach; ?>
  
  
 <script type="text/javascript"> 
    $(function(){
        $(".fancy_table_list").fancybox({
                'titlePosition'		: 'inside',
                'transitionIn'		: 'none',
                'transitionOut'		: 'none',
                "width"           : 400 ,
                "height"           : 400
        });
     });
 </script>
  
</div>
