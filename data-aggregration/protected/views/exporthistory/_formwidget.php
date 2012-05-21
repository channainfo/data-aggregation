<?php $form = $this->beginWidget("CActiveForm", array("id" => "form-siteconfig")); ?>
  <?php echo CHtml::errorSummary($model); ?>

 <style type="text/css">
    #ExportHistory_reversable label{
      display: inline;
      float: none;
      font-weight: normal;
    }
    .row label{
      font-weight: normal;     
    }
  </style>
  <div class="row">
    <?php $model->reversable = ExportHistory::NORMAL ; ?>
    <?php echo $form->labelEx($model, "reversable");  ?>
    <?php echo $form->radioButtonList($model, "reversable", ExportHistory::$REVERSABLE_TYPES, array(
              'separator' => "" ,
              "template"  => "{input} {label} <span style='margin-left:50px;'> </span> ",
              "class" =>"reversable_radio"
              ) );  ?>
    <?php echo $form->error($model, "reversable");  ?>
  </div>
  <!--
  <div class="row">
    <?php echo $form->labelEx($model, "sites");  ?>
    <?php echo $form->dropDownList($model, "sites", SiteConfig::siteListBox(), array("multiple" => 5) );  ?>
    <?php echo $form->error($model, "sites");  ?>
  </div>
  -->
  <br />
  
  <div class="row">
    <?php echo $form->labelEx($model, "all_site", array("style" =>""));  ?>
    <?php echo $form->checkBox($model, "all_site", array("data-rel" => "site_list"));  ?>
    <label style="float:none; display:inline;font-weight: normal;" for="ExportHistory_all_site"> All </label>
    <?php echo $form->error($model, "all_site");  ?>
  </div>

  <div class="row" style="border: 1px solid #ccc;padding:10px;">
    <?php 
      $sites = SiteConfig::siteListBox();
      foreach($sites as $id => $site):
    ?>
    <div class="exportTableItem" >
      <?php echo CHtml::checkBox("ExportHistory[site_list][{$id}]", false, array("value" => $site ,"id" => "site_{$id}", "class" => "site_list" )); ?>
      <?php echo CHtml::label($site, "site_{$id}", array("style" => "float:none; display:inline; font-weight:normal;")); ?>
    </div>  
    <?php endforeach; ?>
    <div class="clear"></div>
  </div>
  <br />
  
  
  
  <div class="row">
    <?php echo $form->labelEx($model, "all_table", array("style" =>""));  ?>
    <?php echo $form->checkBox($model, "all_table", array("data-rel" => "table_list"));  ?>
    <label style="float:none; display:inline;font-weight: normal;" for="ExportHistory_all_table"> All </label>
    <?php echo $form->error($model, "all_table");  ?>
  </div>
  
  <div class="row" style="border: 1px solid #ccc;padding:10px;">
    <?php 
      $tables = ExportHistory::tableList();
      foreach($tables as $table => $columns):
    ?>
        <div class="exportTableItem" >
          <?php echo CHtml::checkBox("ExportHistory[table_list][tables][{$table}]", false, array("id" => $table, "class" =>"table_list")); ?>
          <a href='#fancy_<?php echo $table; ?>' class="fancy_table_list" data-rel="<?php echo $table ?>" >
            <?php echo CHtml::label($table, $table, array("style" => "float:none; display:inline; font-weight:normal;")); ?>
          </a>  
        </div> 
    <?php endforeach; ?>
    <div class="clear"></div>
  </div>
  <!-- Fancy Box  -->
   <?php foreach($tables as $table => $columns):  ?>
    <div style="display:none;"> 
        <div id="fancy_<?php echo $table ?>" >

          <div class="row" >
            <h2 style="font-size: 16px;"> Table : <?php echo $table; ?> </h2>
            <?php $checked_column = true ; ?>
            <?php echo CHtml::label("All columns", $table."_columns" ); ?>
            <?php echo CHtml::checkBox("" ,$checked_column, array("id" => "{$table}_columns","data-rel"=> "{$table}_column_list" )); ?>
          </div>

          <div class="row" style="border: 1px solid #ccc;padding:10px;" > 
            <?php foreach($columns as $column) :  $column_id = "{$table}_{$column}" ;  ?>
                <div class="exportTableItem" >
                  <?php echo CHtml::checkBox("ExportHistory[table_list][columns][$table][$column]", $checked_column , array("id" => $column_id, "class" => "{$table}_column_list" )); ?>
                  <?php echo CHtml::label($column, $column_id , array("style" => "float:none; display:inline; font-weight:normal;")); ?>
                </div> 
            <?php endforeach; ?>
            <div class="clear"> </div>
          </div>
          
        </div>
    </div> 
   <?php endforeach; ?>
   <!-- End Fancy box -->
   <br />

 
  <div class="row">
    <label></label>
    <?php echo CHtml::button("Save", array("id" => "btn-submit")); ?>
  </div>

    <script type="text/javascript" > 
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
      
      function affectedItem(child, afftected){
        $(child).click(function(){
           if(!this.checked){
             $(afftected).attr("checked", false);
           }
        });
      }

      function fancyBox(){
        $(".fancy_table_list").fancybox({
                'titlePosition'		: 'inside',
                'transitionIn'		: 'none',
                'transitionOut'		: 'none',
                "width"           : 500 ,
                "height"           : 400,
                onStart : function(elm){
                  var table = elm[0].getAttribute("data-rel");
                }

        });
      }



      $(function(){
        builtClickAll("ExportHistory_all_table");
        builtClickAll("ExportHistory_all_site");
        <?php foreach($tables as $table => $cols): ?>
          builtClickAll("<?php echo $table ?>_columns");
        <?php endforeach; ?>    

        fancyBox();
        affectedItem(".table_list", "#ExportHistory_all_table");
        affectedItem(".site_list", "#ExportHistory_all_site");
        
        

        $("#btn-submit").click(function(){
          var valid = validate();
          if(valid)
            this.form.submit();
        });

      })

      function validate(){

        var reversables = $(".reversable_radio");
        var valid = false;
        for(var i=0; i< reversables.length ; i++){
          if(reversables[i].checked){
            valid = true;
            break;
          }
        }
        if(!valid){
          alert("Please select a type of export from the export type field ");
          return valid;
        }

        var sites = $(".site_list");
        var valid = false;
        for(var i=0; i<sites.length; i++){
          if(sites[i].checked){
            valid = true;
            break;
          }
        }

        if(valid == false){
          alert("Please select sites");
          return valid;
        }

        valid =false
        var tables = $(".table_list");
        for(var i=0; i<tables.length; i++){
          if(tables[i].checked){
            valid = true;
            break;
          }
        }

        if(valid== false){
          alert("Please select tables");
          return false;
        }
        return true;
      }

    </script>  
  <?php $this->endWidget(); ?>
