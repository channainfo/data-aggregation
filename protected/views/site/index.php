<?php $this->pageTitle=Yii::app()->name; ?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<?php
  if(!Yii::app()->user->isGuest):
    
?>     
You logged in <?php echo date( "Y-m-d H:i:s " , Yii::app()->user->lastLoginTime); ?>
<?php    
  endif;