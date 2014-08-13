neat_html
--------

Prettier html printing of php objects and arrays

## Usage

### Installation

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

### Code

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

##### Multiple Arguments

You can also collect multiple arguments together in a second argument array as follows:

    neat_html($somevar,array("include","comment","return");

Or as a comma or space separated list:

    neat_html($somevar,"include, comment, return");


## License

Neat_html is licensed under the MIT License - see the `LICENSE` file for details

