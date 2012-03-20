<?php
 class DaTool {
   
   public static $messages = array();
   
   public static function p($msg){
     Yii::log($msg);
     echo "\n {$msg}";
   }
   
   /**
    *
    * @param Exception $ex 
    */
   public static function pException($ex, $return = false){
     $message = "[Error] : " . $ex->getMessage() . "\n "
              . "[Code]  : " . $ex->getCode() . "\n "
              . "[Line]  : " . $ex->getLine() . "\n "
              . "[File]  : " . $ex->getFile() 
              ;
     if($return)
       return $message;
     self::p($message);         
   }
   public static function  pd($msg){
     $debug_traces = debug_backtrace();
     $debug_trace = $debug_traces[0];
     echo "\n $msg in Line: {$debug_trace['line']} File: {$debug_trace['file']}";
   }
   
   public static function getYear($date){
     $year = substr($date, 0, 4);
     return $year ; 
   }
   
   public static function pErr($msg){
     echo "\n Err : {$msg}";
     Yii::log($msg,"error");
     DaTool::$messages[] = $msg;
   }
   
   public static function getMessags(){
     echo implode("\n", DaTool::$messages);
   }


   public static function debug($var,$exit=false, $html_format = true){
    $html = <<<EOT
        <div style='text-align:left;border-top:1px solid #ccc;background-color:white;color:black;overflow:auto;' >
            <pre>
                <br /> <strong> line : </strong> {line}
                <br /> <strong> file : </strong> {file}
                <br /> {data}
            </pre>
        </div>
EOT;
    
    $console = <<<EOT
        \n
        -------------------------------debug ---------------------------
        line : {line}, file : {file}
        output: ->
        {data}
        ----------------------------------------------------------------
        \n\r
EOT;
    

        $debug_traces = debug_backtrace();
        $debug_trace = $debug_traces[0];

        $format = $html_format ? $html: $console ;
        $str = strtr($format,
            array( "{line}"=>"{$debug_trace['line']}",
                    "{file}" => "{$debug_trace['file']}",
                    "{data}"=> print_r($var, true) //debug_trace['args'][0]
                 ));
        echo $str ;
        if($exit==true)
            exit;
   }
   
   public static function env($argv){
     $default = "console";
     foreach($argv as $value){
        if(strtolower($value) == "env=test"){
          $default = "test";
          break;
        }
     }
     return $default;
   }
   
   public static function exeCommand($command){
     if(strpos(php_uname("s"), "Window") !==false){
       $WshShell = new COM("WScript.Shell");
       $WshShell->Run($command, 0, false);
     }
     else
       exec("{$command} > /dev/null & ");
   }
   
 }
?>
