neat_r
--------

**neat_r** provides a couple of functions for prettier printing of PHP variables in HTML contexts.

###Usage

    neat_r($somevar)

Will print <code>$somevar</code> in simplified \t and \n notation. Used directly in HTML will render all inline. If you want indentation wrap in <code>&lt;pre&gt;</code> tags.

    neat_html($somevar)

Will print <code>$somevar</code> into html as a <code>&lt;pre&gt;</code>-wrapped block formatted like JSON.

    $output = neat_html($somevar,true)

As with <code>neat_html($somevar)</code> except that the output is returned, captured in the $output variable and not printed.

    neat_html($somevar,"die")

Outputs the formatted <code>$somevar</code> and then dies.

    neat_html($somevar,"comment")

Outputs the formatted code in comment form. comments start <code>$lt;--neat_html</code> for easy searching.

You can also collect multiple arguments together in a second argument array as follows:

    neat_html($somevar,array("comment","return","die");
