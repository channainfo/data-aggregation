<!DOCTYPE html>
<html >
  <head>
    <title><?php echo DaViewHelper::pageTitle($this->pageTitle); ?> </title>
    <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl ?>/css/style.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl ?>/css/main.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl ?>/css/form.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl ?>/css/login.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl ?>/js/fancybox/jquery.fancybox-1.3.4.css" />
    
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/js/modernizr.custom.77819.js" ></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/js/custom.js" ></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/js/fancybox/jquery.mousewheel-3.0.4.pack.js" ></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/js/fancybox/jquery.fancybox-1.3.4.pack.js" ></script>
    
  </head>
  
  <body>
    <div id="main">
      <div class="head" > 
        <h1><a href="" title="nchads" ><?php echo CHtml::image(Yii::app()->request->baseUrl."/images/nchads.jpg","NCHADs") ?> </a></h1>
      </div>
      <div class="breadcrumb" >
        <div style="float: left;" >
          <?php if(isset($this->breadcrumbs)):?>
            <?php $this->widget('zii.widgets.CBreadcrumbs', array('links'=>$this->breadcrumbs,)); ?>
          <?php endif?>
        </div>

        <div style="float:right;" >
          <?php if(!Yii::app()->user->isGuest) : ?>
          Welcome <b><?php echo Yii::app()->user->getName(); ?></b> <span class="item-separator"> | </span>
          <?php echo CHtml::link("Change Password", $this->createUrl("user/change"), array("class" => "btn-link") ) ?> 
          <span class="item-separator"> | </span>
          <?php echo CHtml::link("Logout", $this->createUrl("user/logout") , array("class" => "btn-link")) ?> 
          <?php endif; ?>
        </div>  
        <div style="clear:both;"></div>
      </div>
      <div> </div>
      <?php 
        $flashes = Yii::app()->user->getFlashes();
        if($flashes):
        foreach($flashes as $key => $value): ?>
        <div class=" round flash-<?php echo $key ?>" > <?php echo $value; ?> </div>
      <?php 
        endforeach;
        endif;
      ?>
      <?php echo $content; ?>
     </div> 
    
    <div id="ajax_loading" class="loading round" >Waiting for server response</div>
    
    <script type="text/javascript">
      
      $(function(){
        $(".delete, .confirm").click(function(){

          var message = $(this).attr("data-tip") || "Are you sure to take this action";
          $(".flash-success, .flash-error").hide();

          if(!confirm(message)){
            return false;
          }
          show_loading();
          return true;
        });
          
        $("form").submit(function(){
          show_loading();
        });   
        
        $(".btn-save, .btn-action, .btn-action-edit, .btn-action-new, .btn-link, .breadcrumbs>a, .dashboard-title a").click(function(){
          show_loading();
        });
          
      });
  
    </script>
    
  </body>
</html>