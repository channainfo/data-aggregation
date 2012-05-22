<h1 class="action-title round"> Home </h1>
<div class="dashboard">
<?php echo DaViewHelper::dashboardIcon($this->createUrl("importsitehistory/site") ,"icon-import", "Import", 
        "Aggregation tool is for importing the site database into the combined database OI_SERVER");?>

<?php echo DaViewHelper::dashboardIcon($this->createUrl("importsitehistory/") ,"icon-history", "Import history", 
        "The report of the history of data aggregation");?>

<?php echo DaViewHelper::dashboardIcon($this->createUrl("user/"), "icon-user", "User", 
        "User Management is used to manage the user information. We can browse, add, update, delete user");?>

<?php echo DaViewHelper::dashboardIcon($this->createUrl("siteconfig/"), "icon-config", "Site Config & Restoration", 
        "Connection configuration of all sites databases and their restoration"); ?>
  
<?php echo DaViewHelper::dashboardIcon($this->createUrl("exporthistory/"), "icon-export", "Export data", 
        "Export database to a specific format"); ?>

<?php echo DaViewHelper::dashboardIcon($this->createUrl("conversion/"), "icon-export-convertion", "Conversion", 
        "Conversion of reversible annonymization export to normal format"); ?>

<?php echo DaViewHelper::dashboardIcon($this->createUrl("setting/export"), "icon-export-setting", "Export setting", 
        "Export setting with anonymization"); ?>
  
  
<script type="text/javascript" >
  $(function(){
      $(".icon-wrapper").mouseenter(function(){
        $(this).addClass("background round"); 
      }).mouseleave(function(){
        $(this).removeClass("background round");
      }).click(function(){
        var url = this.getAttribute("data-url");
        window.location.href = url;
      });
  });
</script>
</div>

