<?php
 class DaUtilString {
    public static function truncate($text = '', $length = 150, $suffix = '&hellip;', $isHTML = true){
        $i = 0;
        $tags = array();
        if($isHTML){
            preg_match_all('/<[^>]+>([^<]*)/', $text, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
            foreach($m as $o){
                if($o[0][1] - $i >= $length)
                    break;
                $t = substr(strtok($o[0][0], " \t\n\r\0\x0B>"), 1);
                if($t[0] != '/')
                    $tags[] = $t;
                elseif(end($tags) == substr($t, 1))
                    array_pop($tags);
                $i += $o[1][1] - $o[0][1];
            }
        }
       
        $output = substr($text, 0, $length = min(strlen($text),  $length + $i)) . (count($tags = array_reverse($tags)) ? '</' . implode('></', $tags) . '>' : '');
       
        // Get everything until last space
        $one = substr($output, 0, strrpos($output, " "));
        // Get the rest
        $two = substr($output, strrpos($output, " "), (strlen($output) - strrpos($output, " ")));
        // Extract all tags from the last bit
        preg_match_all('/<(.*?)>/s', $two, $tags);
        // Add suffix if needed
        if (strlen($text) > $length) { $one .= '&nbsp;' . $suffix; } else { $one .= '&nbsp;read'; }
        // Re-attach tags
        $output = $one . implode($tags[0]);
       
        return $output;
    }

 }
?>
