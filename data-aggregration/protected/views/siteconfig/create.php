<h1 class="action-title round"> Site Creation </h1>
<?php
  $this->breadcrumbs = array(
      "Site" => $this->createUrl("siteconfig/"),
      "create" 
      );
?>
<div class="form">
  
<?php $form = $this->beginWidget("CActiveForm", array("id" => "form-siteconfig")); ?>
  <?php echo $form->errorSummary($model); ?>
  <div class="row">
    <?php echo $form->labelEx($model, "code");  ?>
    <?php echo $form->textField($model, "code", array("size" => 60));  ?>
    <?php echo $form->error($model, "code");  ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model, "name");  ?>
    <?php echo $form->textField($model, "name", array("size" => 60));  ?>
    <?php echo $form->error($model, "name");  ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model, "host");  ?>
    <?php echo $form->textField($model, "host", array("size" => 60));  ?>
    <?php echo $form->error($model, "host");  ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model, "db");  ?>
    <?php echo $form->textField($model, "db" , array("size" => 60));  ?>
    <?php echo $form->error($model, "db");  ?>
  </div>
  
  <div class="row">
    <?php echo $form->labelEx($model, "user");  ?>
    <?php echo $form->textField($model, "user", array("size" => 60));  ?>
    <?php echo $form->error($model, "user");  ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($model, "password");  ?>
    <?php echo $form->passwordField($model, "password", array("size" => 60));  ?>
    <?php echo $form->error($model, "password");  ?>
  </div>

  <div class="row">
    <label></label>
    <?php echo CHtml::button("Test connection", array("id" => "test-connection")); ?>
    <?php echo CHtml::submitButton("Save", array("id" => "save-btn")); ?>
  </div>
  
  <script type="text/javascript">
    $(function(){
      var message = "Failed to connect " ;
      $("#test-connection").click(function(){
         $.ajax({
           url: "<?php echo Yii::app()->createUrl("siteconfig/testConnection") ?>",
           success: function(response){
             if(response == "true"){
               $("#save-btn").attr("disabled", false) ;
               alert("you are successfully connected")
             }
             else{
               alert(message);
             }
           },
           failure: function(response){
             alert(message);
           },
           data: $("#form-siteconfig").serialize()
         })
      });
    });
  </script>
  
<?php $this->endWidget(); ?>
  
</div>

