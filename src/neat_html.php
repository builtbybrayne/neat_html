<?php

/**
 * Helper class for neat_html debugging
 * */
class Neat_Html {
    private static $enabled = true;
    private static $defaults = array();

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


    public static function setDefault($key) {
        self::$defaults[$key] = $key;
    }

    public static function setDefaults($defaults) {
        self::$defaults = array();
        foreach ( $defaults as $v) {
            self::setDefault($v);
        }
    }
    public static function getDefaults() {
        return self::$defaults;
    }

    public static function removeDefault($key) {
        unset(self::$defaults[$key]);
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

    $fargs = func_get_args();
    if ( count($fargs) > 2 ) {
        $args = $fargs;
        array_shift($args);
    }

    foreach ( Neat_Html::getDefaults() as $default ) {
        $args[] = $default;
    }

    $die = false;
    $return = false;
    $comment = false;
    $include = false;
    $json = false;
    $nopre = false;
    $php = false;
    $dump = false;

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
            if ( $arg == "die"     ) $die = true;
            if ( $arg == "return"  ) $return = true;
            if ( $arg == "comment" ) $comment = true;
            if ( $arg == "include" ) $include = true;
            if ( $arg == "json"    ) $json = true;
            if ( $arg == "nopre"   ) $nopre= true;
            if ( $arg == "php"     ) $php= true;
            if ( $arg == "dump"    ) $dump= true;
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

    if ( $dump ) {
        var_dump($arr);
    }

    // End of the input data manipulation. Now beginning formatting

    if ( $json ) {
        $arr = json_encode($arr);
    }

    $printFn = 'print_r';
    if ( $php ) {
        $printFn = 'var_export';
    }

    $str = "";
    if ( $comment ) $str .= "<!--neat_html ";
    if ( !$json && !$nopre ) { $str .= "<pre style=\"color:black; white-space: pre-wrap\">\n"; }
    $str .= $printFn($arr,true);
    if ( !$json && !$nopre ) { $str .= "</pre>\n"; }
    if ( $comment ) $str .= "-->";

    if ($return == true)
        return $str;
    echo $str;

    if ( $die ) die();
}


