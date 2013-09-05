<?php

if ( !function_exists("truepath") ) {
    /**
     * This function is to replace PHP's extremely buggy realpath().
     * @param string The original path, can be relative etc.
     * @return string The resolved path, it might not exist.
     */
    function truepath($path){
        // whether $path is unix or not
        $unipath=strlen($path)==0 || $path{0}!='/';
        // attempts to detect if path is relative in which case, add cwd
        if(strpos($path,':')===false && $unipath)
            $path=getcwd().DIRECTORY_SEPARATOR.$path;
        // resolve path parts (single dot, double dot and double delimiters)
        $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
        $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
        $absolutes = array();
        foreach ($parts as $part) {
            if ('.'  == $part) continue;
            if ('..' == $part) {
                array_pop($absolutes);
            } else {
                $absolutes[] = $part;
            }
        }
        $path=implode(DIRECTORY_SEPARATOR, $absolutes);
        // resolve any symlinks
        if(file_exists($path) && linkinfo($path)>0)$path=readlink($path);
        // put initial separator that could have been lost
        $path=!$unipath ? '/'.$path : $path;
        $path=($path{0}!="/") ? '/'.$path : $path;
        return $path;
    }
}


if ( !function_exists("neat_r") ) {
    function neat_r($arr, $return = false) {
        $out = array();
        $oldtab = " ";
        $newtab = " ";

        $lines = explode("\n", print_r($arr, true));

        foreach ($lines as $line) {

//remove numeric indexes like "[0] =>" unless the value is an array
            if (substr($line, -5) != "Array") { $line = preg_replace("/^(\s*)\[[0-9]+\] => /", "$1", $line, 1); }

//garbage symbols
            foreach (array(
                         "Array" => "",
                         "[" => "",
                         "]" => "",
                         " =>" => ":",
                     ) as $old => $new) {
                $out = str_replace($old, $new, $out);
            }

//garbage lines
            if (in_array(trim($line), array("Array", "(", ")", ""))) continue;

//indents
            $indent = "";
            $indents = floor((substr_count($line, $oldtab) - 1) / 2);
            if ($indents > 0) { for ($i = 0; $i < $indents; $i++) { $indent .= $newtab; } }

            $out[] = $indent . trim($line);
        }

        $out = implode("\n", $out) . "\n";
        if ($return == true) return $out;
        echo $out;
    }
}

if ( !function_exists('neat_html') ) {
    function neat_html($arr, $args=null) {
        $die = false;
        $return = false;
        $comment = false;
        $include = false;
        $json = false;
        if ( is_bool($args) ) $return = $args;
        if ( is_string($args) ) $args = array($args);
        if ( is_array($args) ) {
            foreach ( $args as $arg ) {
                if ( $arg == "die" ) $die = true;
                if ( $arg == "return" ) $return = true;
                if ( $arg == "comment" ) $comment = true;
                if ( $arg == "include" ) $include = true;
                if ( $arg == "json" ) $json = true;
            }
        }

        if ( $arr===true )
            $arr="True";
        else if ( $arr===false )
            $arr="False";
        if ( $arr === null ) {
            $arr="Null";
        }


        if ( $include ) {
            ob_start();
            include $arr;
            $arr = ob_get_clean();
        }

        if ( $json ) {
            $arr = json_encode($arr);
        }

        $str = "";
        if ( $comment ) $str .= "<!--neat_html ";
        if ( !$json) { $str .= "<pre style=\"color:black\">\n"; }
        $str .= print_r($arr,true);
        if ( !$json ) { $str .= "</pre>\n"; }
        if ( $comment ) $str .= "-->";
        if ($return == true) return $str;
        echo $str;
        if ( $die ) die();
    }
}