neat_html
--------

Prettier html printing of php objects and arrays

## Installation

##### Via Composer

Neat_html is available on Packagist ([perchten/neat_html](https://packagist.org/packages/perchten/neat_html)) and as such is installable via [Composer](https://getcomposer.org/).

Add the following to your `composer.json`

	{
    	"require": {s
        	"perchten/neat_html": "1.*"
	    }
	}

##### Direct include

Clone or download from [GitHub](https://github.com/perchten/neat_html) and include directly in your code:

	require_once "path/to/neat_html.php"

## Usage

##### Simple neat_html

    neat_html($somevar)

Will print <code>$somevar</code> into html as a <code>&lt;pre&gt;</code>-wrapped block formatted like JSON.

##### Return, Don't Print
    $output = neat_html($somevar,true)

As with <code>neat_html($somevar)</code> except that the output is returned, captured in the $output variable and not printed.

##### Die Immediately After Debug Print

    neat_html($somevar,"die")

Outputs the formatted <code>$somevar</code> and then dies.

##### Output as Html Comment

    neat_html($somevar,"comment")

Outputs the formatted code in comment form. comments start <code>&lt;!--neat_html</code> for easy searching.

##### Do not wrap in `<pre>` tags

    neat_html($somevar,"nopre")

Outputs the formatted code without wrapping it in `<pre>` tags. Useful for console printing.

##### Print Included Files

    neat_html($somefileref,'include)

Takes $somefileref as a file reference and includes that file's contents. Obviously, if the included file uses undefined variables then it will fail.

Also, the file reference must be absolute. For convenience a `truepath` function is included to get the real path. (PHP's realpath() function is a bit buggy, so best not use that. see http://stackoverflow.com/questions/4049856/replace-phps-realpath). e.g.

    neat_html(truepath($somefileref),'include')

##### Output in JSON (Handy For Ajax)

    neat_html($somevar,'json')

Returns the object in json notation. Handy when debugging over ajax that expects a json response.

##### Output in PHP (Handy For Purists ;))

    neat_html($somevar,'php')

Returns the object in php notation. This then uses the [var_export](http://php.net/manual/en/function.var-export.php) function instead of `print_r`. So the returned values here can even be interpreted directly as php variables.


##### Multiple Arguments

You can also collect multiple arguments together in a second argument array as follows:

    neat_html($somevar,array("include","comment","return");

Or as a comma or space separated list:

    neat_html($somevar,"include, comment, return");
    
And, as of `v1.2` you can even use dynamic arguments:

	neat_html($somevar,"include","comment","return");
	

##### var_dump

    neat_html($somevar,'dump');

This will run all data manipulation on `$somevar` (including if it is a file include), and then `var_dump` the result _in addition_ to outputting in any other specified formatting.


##### Defaults

You can set defaults at a global level if you find yourself repeating the same optional arguments all the time.

	Neat_Html::setDefault($option);
	
	Neat_Html::getDefaults();
	
	Neat_Html::setDefaults($optionsArray);
	
	Neat_Html::removeDefault($option);	
    


##### Enabling/Disabling 

`neat_html` statements will run by default, but you can control this by switching the functionality on and off globally, and querying the current state.

    Neat_Html::setOn() // switches this module on globally
  
    Neat_Html::setOff() // switches this module off globally
    
    Neat_Html::isOn() // return true if module is on, otherwise false
    

## License

Neat_html is licensed under the MIT License - see the `LICENSE` file for details

