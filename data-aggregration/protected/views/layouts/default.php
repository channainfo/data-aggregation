<!DOCTYPE html>
<html >
  <head>
    <title><?php echo DaViewHelper::pageTitle($this->pageTitle); ?> </title>
    <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl ?>/css/style.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl ?>/css/main.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl ?>/css/form.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl ?>/css/login.css" />
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/js/modernizr.custom.77819.js" ></script>
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
          Welcome <?php echo Yii::app()->user->getName(); ?> <span class="item-separator"> | </span>
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
    <script type="text/javascript">
      $(function(){
        $(".delete").click(function(){
          if(!confirm("Are you sure to delete")){
            return false;
          }
          return true;
        })
      });
  
    </script>
  </body>
</html>