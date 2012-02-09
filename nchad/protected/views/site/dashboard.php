
<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
<h1 class="action-title round"> Dashboard </h1>
<?php echo VViewHelper::dashboardIcon("user" ,"icon-import", "Import", 
        "Aggregration tool is for importing the site database into the combined database OI_SERVER");?>

<?php echo VViewHelper::dashboardIcon("user" ,"icon-history", "Import history", 
        "The report of the history of data aggregation");?>

<?php echo VViewHelper::dashboardIcon("user", "icon-user", "User", 
        "User Management is used to manage the user information. We can browse, add, update, delete user");?>

<?php echo VViewHelper::dashboardIcon("user", "icon-config", "Site Config & Restoration", 
        "Connection configuration of all sites databases and their restoration"); ?>
<script type="text/javascript" >
  $(function(){
      $(".icon-wrapper").mouseenter(function(){
        $(this).addClass("background"); 
      }).mouseleave(function(){
        $(this).removeClass("background");
      }).click(function(){
        var url = this.getAttribute("data-url");
        window.location.href = url;
      });
  });
</script>

