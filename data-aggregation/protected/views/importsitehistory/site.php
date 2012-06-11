<?php
  $this->breadcrumbs = array(
    "Databases"
  )
?>
<h1 class="action-title round"> Import data list </h1>
<?php if(count($sites)): ?>
<div class="tableWrapper round">
  <table class="tgrid" >
    <thead>
      <tr>
        <th> Site </th>
        <th> Site name </th>
        <th> Db Server </th>
        <th> Db name </th>
        <th> Last restore </th>
        <th> Last import </th>
        <th> Import status </th>
        <th> Action </th>
      </tr>
    </thead>
  <?php 
  $i =0 ;
   foreach($sites as $row): ?>
    <tr class="<?php echo $i%2?"even":"odd" ?>">
      <td> <?php echo CHtml::link($row->code, $this->createUrl("siteconfig/update/{$row->id}"), array("class" => "btn-link underline")); ?>  </td>
      <td> <?php echo $row->name ; ?>  </td>
      <td> <?php echo $row->host; ?>  </td>
      <td> <?php echo $row->db; ?>  </td>
      <td> <?php echo $row->last_restored ; ?> </td>
      <td> <?php echo $row->last_imported ?> </td>
      <td> 
        <?php 
          if($row->status !== NULL) : 
            $status = $row->getStatusText();
          ?>
          <span class="state <?php echo "{$status}-state"  ?> " >
            <?php echo CHtml::link(ucfirst($status), $this->createUrl("importsitehistory/index", array("siteconfig_id" => $row->id)), array()) ?>
          </span>
        <?php else: ?>
           -
        <?php endif; ?>
      </td>
      <td> 
        <?php 
        
        if( $row->isImportable()): ?> 
          <span class="disabled round"> Waiting to be imported </span>
        <?php elseif($row->isImporting()): ?>
          <span class="disabled round"> In progress </span>
        <?php elseif(Yii::app()->user->isAdmin())  :?>
          <?php echo CHtml::link("Start Import", $this->createUrl("importsitehistory/import", array("siteconfig_id"=>"{$row->id}")),
                array("class" => "btn-action-delete confirm round", "data-tip" => "Are you sure to import to site: {$row->name}" ) ) ?> 
        <?php endif; ?>
      </td>
    </tr>
  <?php $i++; endforeach ; ?>
  </table>
  
  <br />
  <div class="right-align">
    <?php $this->widget("CLinkPager", array("pages" => $pages)) ; ?>
  </div>  
  <div class="clear"></div>
  <br />
</div>
<?php endif; ?>

