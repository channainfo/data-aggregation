<?php
 
?>
<table>
  <tr>
    <th>Code</td>
    <td> : <?php echo CHtml::link($siteconfig->code, $this->createUrl("siteconfig/update/{$siteconfig->id}")); ?></td>
  </tr>
  <tr>
    <th width="150" >Name</td>
    <td> : <?php echo $siteconfig->name; ?></td>
  </tr>
  <tr>
    <th>Db</td>
    <td> : <?php echo $siteconfig->db ?> </td>
  </tr>
  <tr>
    <th>Server</td>
    <td> : <?php echo $siteconfig->host ?></td>
  </tr>
  <tr><td colspan="2">&nbsp;</td></tr>
</table>
