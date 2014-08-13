<?php

/**
 * Helper class for neat_html debugging
 * */
class Neat_Html {
    private static $enabled = true;

    /*
     * Switch on neat_html functionality, globally
     */
    public static function on() {
        self::$enabled = true;
    }
    /*
     * Switch off neat_html functionality, globally
     */
    public static function off() {
        self::$enabled = false;
    }

    /*
     * @return true if neat_html is enabled globally
     */
    public static function isOn(){
        return self::$enabled;
    }
}

/**
 * This function - particularly the second $args parameter can be heavily overloaded.
 * (Also allows dynamic argument definitions)
 *
 * The best documentation is available at https://github.com/perchten/neat_html
 *
 * @param $arr
 * @param null $args...
 * @return string
 */
function neat_html($arr, $args=null) {
    if ( !Neat_Html::isOn() )
        return "";

    $args = func_get_args();
    array_shift($args);

    $die = false;
    $return = false;
    $comment = false;
    $include = false;
    $json = false;
    $nopre = false;
    if ( is_bool($args) ) $return = $args;
    if ( is_string($args) ) {
        $args = explode(",",$args);
        if ( count($args)==1 ) {
            $args = explode(" ",$args[0]);
        }
    }
   
    if ( is_array($args) ) {
        foreach ( $args as $arg ) {
            $arg = trim($arg);
            if ( $arg == "die" ) $die = true;
            if ( $arg == "return" ) $return = true;
            if ( $arg == "comment" ) $comment = true;
            if ( $arg == "include" ) $include = true;
            if ( $arg == "json" ) $json = true;
            if ( $arg == "nopre" ) $nopre= true;
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
    if ( !$json && !$nopre ) { $str .= "<pre style=\"color:black; white-space: pre-wrap\">\n"; }
    $str .= print_r($arr,true);
    if ( !$json && !$nopre ) { $str .= "</pre>\n"; }
    if ( $comment ) $str .= "-->";
    if ($return == true) return $str;
    echo $str;
    if ( $die ) die();
}


