<?php
  $this->breadcrumbs = array(
      "Import data" => $this->createUrl("importhistory/site"),
      "Import History"
  )
?>
<?php $this->renderPartial("//siteconfig/_detail", array("siteconfig" => $siteconfig)) ?>

<?php if(count($importHistories)): ?>
<?php if($importHistories[0]->restorable()) : ?>
  <!-- <div class="restore round" > Restoring ...  </div> -->
  <script type="text/javascript">
   $(function(){
     show_loading("Restoring" );
      $.ajax({
        url: "<?php echo Yii::app()->createUrl("", array("id" => $siteconfig->id )) ?>",
        cache: false,
        dataType: "json",
        success: function(response){
        },
        complete:function(){
          hide_loading();
        }
      });
   }); 
    
  </script>
<?php  endif; ?>

<div class="tableWrapper round">
    <table class="tgrid">
      <thead>
        <tr>
          <th width="120"> Date </th>
          <th width="50"> Status </th>
          <th> Reason </th>

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
          <td> <?php echo date("Y-m-d", strtotime($importHistory->created_at) ); ?> </td>
          <td> <span class="state <?php echo "{$status}-state"  ?> <?php echo $cls; ?>" ><?php echo ucfirst($status) ?></span></td>
          <td> <?php echo $importHistory->reason; ?>
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
<?php endif; ?>

