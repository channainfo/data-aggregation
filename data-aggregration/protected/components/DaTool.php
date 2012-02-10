<?php
 class DaTool {
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
        $debug_trace=$debug_traces[0];

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
 }
?>
