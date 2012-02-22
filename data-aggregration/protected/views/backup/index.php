<?php
  $this->breadcrumbs = array(
      "Sites" => $this->createUrl("siteconfig/index"),
      "Restorations"
  )
?>
<?php echo DaViewHelper::titleActionGroup("Restorations history", CHtml::link("New", $this->createUrl("backup/create", array("siteconfig_id" => $siteconfig->id)), array("class" => "btn-action-new round"))) ?>
<?php $this->renderPartial("//siteconfig/_detail", array("siteconfig" => $siteconfig)) ?>

<?php if(count($backups)): ?>
<?php if($backups[0]->restorable()) : ?>
  <!-- <div class="restore round" > Restoring ...  </div> -->
  <script type="text/javascript">
   $(function(){
     show_loading("Restoring" );
      $.ajax({
        url: "<?php echo Yii::app()->createUrl("siteconfig/restore", array("id" => $siteconfig->id )) ?>",
        cache: false,
        dataType: "json",
        success: function(response){
        },
        complete:function(){
          hide_loading();
          window.location = window.location.href ;
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
          <th> Name </th>
          <th width="50"> Status </th>
          <th> Reason </th>

        </tr>
      </thead>
    <?php 
    $i =0 ;
    foreach ($backups as $backup): ?>
        <?php $status = $backup->getStatusText();  ?>
        <?php
          $cls ="";
          if($backups[0]->restorable() && $i == 0)
            $cls = "restoring";
        ?>
        <tr class="<?php echo $i%2 == 0 ? "even" : "add" ?>" >
          <td> <?php echo $backup->created_at; ?> </td>
          <td> <?php echo basename($backup->filename); ?> </td>
          <td><span class="state <?php echo "{$status}-state"  ?> <?php echo $cls; ?>" ><?php echo ucfirst($status) ?></span></td>
          <td> <?php echo $backup->reason; ?>
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

