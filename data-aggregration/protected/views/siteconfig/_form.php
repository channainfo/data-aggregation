<div class="form">
  <div class="formfade"></div>
<?php $this->renderPartial("//shared/_requireField"); ?>  
<?php $form = $this->beginWidget("CActiveForm", array("id" => "form-siteconfig")); ?>
  <?php echo CHtml::errorSummary($model); ?>
  <div class="row">
    <?php echo $form->labelEx($model, "code");  ?>
    <?php echo $form->textField($model, "code", array("size" => 60, "autocomplete" => "off"));  ?>
    <?php echo $form->error($model, "code");  ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model, "name");  ?>
    <?php echo $form->textField($model, "name", array("size" => 60, "autocomplete" => "off"));  ?>
    <?php echo $form->error($model, "name");  ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model, "host");  ?>
    <?php echo $form->textField($model, "host", array("size" => 60, "autocomplete" => "off"));  ?>
    <?php echo $form->error($model, "host");  ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model, "db");  ?>
    <?php echo $form->textField($model, "db" , array("size" => 60, "autocomplete" => "off"));  ?>
    <?php echo $form->error($model, "db");  ?>
  </div>
  
  <div class="row">
    <?php echo $form->labelEx($model, "user");  ?>
    <?php echo $form->textField($model, "user", array("size" => 60, "autocomplete" => "off"));  ?>
    <?php echo $form->error($model, "user");  ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model, "password");  ?>
    <?php echo $form->passwordField($model, "password", array("size" => 60, "autocomplete" => "off"));  ?>
    <?php echo $form->error($model, "password");  ?>
  </div>

  <div class="row">
    <label></label>
    <?php echo CHtml::button("Test connection", array("id" => "test-connection")); ?>
    <?php echo CHtml::submitButton("Save", array("class" => "btn-save")); ?>
  </div>
  
  <script type="text/javascript">
    function collape(message){
        content = "<div class='errorContent'>" + message + "</div>";
        return content;
    }
    $(function(){
      var message = "Failed to connect <br />" ;
      $("#test-connection").click(function(){
         show_loading(null, ".form");
         $(".formfade").html("");
         $.ajax({
           url: "<?php echo Yii::app()->createUrl("siteconfig/testConnection") ?>",
           cache: false,
           success: function(errResponse){
             if(errResponse == ""){
               $("#save-btn").attr("disabled", false) ;
               fadeNotification("You are successfully connected", ".formfade");
             }
             else{
               fadeNotification(message + collape(errResponse), ".formfade", 1000*60*60);
             }
           },
           failure: function(errResponse){
             fadeNotification(message + errResponse, ".formfade");
           },
           complete: function(){
             hide_loading();
           },
           data: $("#form-siteconfig").serialize()
         })
      });
    });
  </script>
  
<?php $this->endWidget(); ?>
</div>
