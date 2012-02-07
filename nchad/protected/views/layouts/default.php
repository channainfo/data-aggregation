<!DOCTYPE html>
<html >
  <head>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl ?>/css/style.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl ?>/css/main.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl ?>/css/form.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl ?>/css/form.css" />
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/js/modernizr.custom.77819.js" ></script>
    
  </head>
  
  <body>
    <div id="main">
      <div class="head" > 
        <h1><a href="" title="nchad" ><img src="nchad.png" alt="nchad" </a></h1>
      </div>
      <div style="border-bottom: 1px solid #ccc;">
        <div style="float: left;" >
          <?php if(isset($this->breadcrumbs)):?>
            <?php $this->widget('zii.widgets.CBreadcrumbs', array('links'=>$this->breadcrumbs,)); ?>
          <?php endif?>
        </div>

        <div style="float:right;" >
          <?php if(!Yii::app()->user->isGuest) : ?>
          Welcome <?php echo Yii::app()->user->getName(); ?> |
          <a href="#" > Change Password </a> |
          <a href='#' > Logout </a> 

          <?php endif; ?>
        </div>  
        <div style="clear:both;"></div>
      </div>
      <div> </div>
      <?php echo $content; ?>
     </div> 
  </body>
</html>