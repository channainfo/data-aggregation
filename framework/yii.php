<?php
/**
 * Yii bootstrap file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 * @version $Id: yii.php 2799 2011-01-01 19:31:13Z qiang.xue $
 * @package system
 * @since 1.0
 */

require(dirname(__FILE__).'/YiiBase.php');

/**
 * Yii is a helper class serving common framework functionalities.
 *
 * It encapsulates {@link YiiBase} which provides the actual implementation.
 * By writing your own Yii class, you can customize some functionalities of YiiBase.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: yii.php 2799 2011-01-01 19:31:13Z qiang.xue $
 * @package system
 * @since 1.0
 */
define("CH_DEBUG",1);
define("CH_TEST", 0 );
class Yii extends YiiBase
{
  public static function ch_debug($var,$exit=false,$configable=true){
      $templates[] = <<<EOT
      <div style='text-align:left;border-top:1px solid #ccc;background-color:white;color:black;overflow:auto;' > 
          <pre>
            <br /> <strong> line : </strong> {line}
            <br /> <strong> file : </strong> {file} 
            <br /> {data}
          </pre>
      </div>   
EOT;
       $templates[]= <<<EOT
       \n
        -------------------------------debug ---------------------------
        line : {line}, file : {file}
        output: ->
        {data}
        ----------------------------------------------------------------
        \n\r
EOT;
       
       
	if(CH_DEBUG  || $exit==true)
	{
           $debug_traces = debug_backtrace();
           $debug_trace=$debug_traces[0];

           $str = strtr($templates[CH_TEST], 
                   array( "{line}"=>"{$debug_trace['line']}", 
                          "{file}" => "{$debug_trace['file']}", 
                          "{data}"=> print_r($var, true) //debug_trace['args'][0] 
                        ));
           echo $str ;
           if($exit==true)
            exit;
	}
    }  
}
