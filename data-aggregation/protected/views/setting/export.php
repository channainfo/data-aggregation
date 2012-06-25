<?php
  $this->breadcrumbs = array(
      "Setting"
  );
 ?>
 <?php echo CHtml::beginForm(); ?>
  <h1 class="action-title round"> Setting  </h1>
  <h2> Export setting </h2>
  <div class="row" style="border: 1px solid #ccc;padding:10px;">
    <?php 
      $configs = DaConfig::importConfig();
      $tables = array_merge($configs["tables"], $configs["fixed"]);
      $i=0;
      $count  = count($tables) ;
      $ncolumn = round($count/4);
      
      foreach($tables as $table => $columns):
       if($i == 0)
         echo "<div class='exportTableItem' >" ;
        
       elseif($i%$ncolumn == 0)
         echo "</div><div class='exportTableItem'>" ;
    ?>
        <div >
          <a href='#fancy_<?php echo $table; ?>' class="fancy_table_list" data-rel="<?php echo $table ?>" ><?php echo $table ?></a>  
        </div> 
    <?php
      $i++ ;
      if($i == $count)
        echo "</div>" ;
      
      endforeach; 
    ?>
    <div class="clear"></div>
  </div>
  <!-- Fancy Box  -->
   <?php foreach($tables as $table => $columns):  ?>
    <div style="display:none;"> 
        <div id="fancy_<?php echo $table ?>" >

          <div class="row" >
            <h2 style="font-size: 16px;"> Please select columns to anonymise for table : <?php echo $table; ?> </h2>
            
            <?php echo CHtml::label("All columns", $table."_columns" ); ?>
            <?php echo CHtml::checkBox("" ,false, array("id" => "{$table}_columns","data-rel"=> "{$table}_column_list" )); ?>
          </div>

          <div class="row" style="border: 1px solid #ccc;padding:10px;" > 
            <?php foreach($columns as $column) : 
              $column_id = "{$table}_{$column}" ;
              $checked_column = false ; 
              if( isset($settings[$table]) &&  array_search($column, $settings[$table]) !== false )
                $checked_column = true ; 
            
            ?>
                <div class="exportTableItem" >
                  <?php echo CHtml::checkBox("Setting[export][$table][$column]", $checked_column , array("id" => $column_id, "class" => "{$table}_column_list" )); ?>
                  <?php echo CHtml::label($column, $column_id , array("style" => "float:none; display:inline; font-weight:normal;")); ?>
                </div> 
            <?php endforeach; ?>
            <div class="clear"> </div>
          </div>
          
        </div>
    </div> 
   <?php 
   $i++ ;
   endforeach; ?>
   <!-- End Fancy box -->
   <br />

 
  <div class="row">
    <?php if(Yii::app()->user->isAdmin()): ?>
    <label></label>
    <?php echo CHtml::submitButton("Save", array("id" => "btn-submit")); ?>
    <?php endif;?>
  </div>
<?php echo CHtml::endForm(); ?>
<script type="text/javascript">
   function fancyBox(){
      $(".fancy_table_list").fancybox({
              'titlePosition'		: 'inside',
              'transitionIn'		: 'none',
              'transitionOut'		: 'none',
              "width"           : 500 ,
              "height"           : 400
      })
   }
   
   function builtClickAll(id){
        $( "#" + id ).click(function(){

          $self = $(this);
          cls = $self.attr("data-rel");
          $list = $("."+cls);

          for(var i=0; i<$list.length; i++){
            $list[i].checked = this.checked;
          }
        })
   }
   
  $(function(){
    fancyBox();
    <?php foreach($tables as $table => $cols): ?>
       builtClickAll("<?php echo $table ?>_columns");
    <?php endforeach; ?> 
  });
</script>