<?php



class Neat {

    private static $enabled = true;
    private static $defaults = array();

    private $str;

    private $die = false;
    private $return = false;
    private $comment = false;
    private $include = false;
    private $json = false;
    private $nopre = false;
    private $php = false;
    private $dump = false;

    public function read($input,$args=null) {

        $this->str = $this->sanitiseInput($input);

        $fArgs = func_get_args();
        if ( count($fArgs)>2 ) {
            $args = $fArgs;
            array_shift($args);
        }
        $this->readArgs($args);

        return $this;

    }

    public function readArgs($args) {

        foreach ( Neat::getDefaults() as $default ) {
            $args[] = $default;
        }

        if ( is_bool($args) ) $this->return = $args;
        if ( is_string($args) ) {
            $args = explode(",",$args);
            if ( count($args)==1 ) {
                $args = explode(" ",$args[0]);
            }
        }

        if ( is_array($args) ) {
            foreach ( $args as $arg ) {
                $arg = trim($arg);
                if ( $arg == "die"     ) $this->die = true;
                if ( $arg == "return"  ) $this->return = true;
                if ( $arg == "comment" ) $this->comment = true;
                if ( $arg == "include" ) $this->include = true;
                if ( $arg == "json"    ) $this->json = true;
                if ( $arg == "nopre"   ) $this->nopre= true;

                if ( $arg == "php"     ) $this->php= true;
                if ( $arg == "dump"    ) $this->dump= true;
            }
        }
    }

    private function sanitiseInput($input) {
        if ( $input===true )
            $input="True";
        else if ( $input===false )
            $input="False";
        if ( $input === null ) {
            $input="Null";
        }
        return $input;
    }

    public function go() {
        if ( !Neat::isOn() )
            return "";

        $input = $this->str;

        if ( $this->include ) {
            ob_start();
            include $input;
            $input = ob_get_clean();
        }

        if ( $this->dump ) {
            var_dump($input);
        }

        if ( $this->json ) {
            $input = json_encode($input);
        }

        $printFn = 'print_r';
        if ( $this->php ) {
            $printFn = 'var_export';
        }

        $str = "";
        if ( $this->comment ) $str .= "<!--Neat ";
        if ( !$this->json && !$this->nopre ) { $str .= "<pre style=\"color:black; white-space: pre-wrap\">\n"; }
        $str .= $printFn($input,true);
        if ( !$this->json && !$this->nopre ) { $str .= "</pre>\n"; }
        if ( $this->comment ) $str .= "-->";

        $this->str = $str;

        if ( !$this->return ) {
            echo $this->str;
        }

        if ( $this->die ) die();

        return $this;
    }

    public static function in($arr,$args=null) {
        return ( Neat::isOn() ) ? call_user_func_array(array(new Neat(),"read"),func_get_args()) : new Neat();
    }
    public static function pp($arr, $args=null) {
        if ( !Neat::isOn() )
            return new Neat();
        $neat = call_user_func_array(array(new Neat(),"in"),func_get_args());
        return $neat->go();
    }


    public function json() {
        $this->json = true;
        return $this;
    }

    public function php() {
        $this->php = true;
        return $this;
    }

    public function noPre() {
        $this->nopre = true;
        return $this;
    }

    public function inc() {
        $this->include = true;
        return $this;
    }

    public function di() {
        $this->die = true;
        return $this;
    }

    public function rtrn() {
        $this->return = true;
        return $this;
    }

    public function dump() {
        $this->dump = true;
        return $this;
    }

    public function cmnt() {
        $this->comment = true;
        return $this;
    }

    public function __toString() {
        return $this->str;
    }

    /*
     * Switch on Neat functionality, globally
     */
    public static function on() {
        self::$enabled = true;
    }
    /*
     * Switch off Neat functionality, globally
     */
    public static function off() {
        self::$enabled = false;
    }

    /*
     * @return true if Neat is enabled globally
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
 * @deprecated
 */
function neat_html($arr, $args=null) {
    if ( !Neat::isOn() )
        return new Neat();
    $neat = ( Neat::isOn() ) ? call_user_func_array(array(new Neat(),"read"),func_get_args()) : new Neat();
    return $neat->go();
}


