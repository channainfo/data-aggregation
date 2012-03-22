<?php
 class DaViewHelper {
   public static function dashboardIcon($url, $cssClass, $title, $description){
     $template = <<<EOT
     <div class="icon-wrapper" data-url="$url" > 
        <div class="icon-dashboard $cssClass">
          <h2 class="dashboard-title"> <a href='$url' >  $title </a> </h2>
          <p class="descritpion">
            $description
          </p>
        </div>
        
      </div> 
     
EOT;
     return $template;
     
   }
   
   public static function pageTitle($title){
     if(!empty($title)){
       return $title." - ".Yii::app()->name ;
     }
     return Yii::app()->name;
   }
   
  public static function titleActionGroup($title, $link_content){
    $template = <<<EOT
    <div class="action-title round"> 
      <div class="action-bar-left"> $title </div>
      <div class="action-bar-right"> 
        $link_content
      </div>
      <div class="clear"></div>
    </div>

EOT;
    return $template;
    
  } 
  /**
   *
   * @param array $var 
   */
  public static function  outputVars($var){
    if(!empty($var)): ?>
      <div class="tableWrapper round" >
        <table class="tgrid" >
            <?php
            $i = 0 ;
            foreach($var as $key => $value): ?>
            <tr class="<?php echo $i%2? "odd": "event"; ?>">
              <td> <?php echo $key ?> </td>
              <td> <?php echo $value; ?> </td>
            </tr>
            <?php $i++; endforeach; ?>
        </table>
      </div>
    <?php endif;
  }
  
  public static function htmlControlErrorOutput($err, $return= true){
    $str =  str_replace(array("[", "]"), array("<b>","</b>"), $err);
    if($return)
      return $str;
    echo $str;
  }
  
 }
 
 
?>
