<?php
$this->breadcrumbs=array(
	'Message'=>array('message/index'),
	'HelloWorld',
);?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>
<p>
    <?php echo $_time ?>
    
</p>

<?php echo CHtml::link("Blah", array("message/index")) ?>

<p>You may change the content of this page by modifying the file <tt><?php echo __FILE__; ?></tt>.</p>
