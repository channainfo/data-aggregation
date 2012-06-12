<!DOCTYPE html>
<html >
  <head>
    <title><?php echo DaViewHelper::pageTitle($this->pageTitle); ?> </title>
    <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl ?>/css/style.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl ?>/css/main.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl ?>/css/form.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl ?>/css/login.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl ?>/css/jquery-ui-1.7.3.custom.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl ?>/js/fancybox/jquery.fancybox-1.3.4.css" />
    
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/js/modernizr.custom.77819.js" ></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/js/custom.js" ></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/js/fancybox/jquery.mousewheel-3.0.4.pack.js" ></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/js/fancybox/jquery.fancybox-1.3.4.pack.js" ></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/js/jquery-ui-1.7.3.custom.min.js" ></script>
    
  </head>
  
  <body>
    <div id="main" class="width">
      <div class="head" > 
        <h1><a href="<?php echo $this->createUrl("/"); ?>" title="nchads"  ><?php echo CHtml::image(Yii::app()->request->baseUrl."/images/nchads.jpg","NCHADs") ?> </a></h1>
      </div>
      <div> </div>
      
      <?php echo $content; ?>
    </div> 
    <div id="ajax_loading" class="loading round" >Waiting for server response</div>
  </body>
</html>