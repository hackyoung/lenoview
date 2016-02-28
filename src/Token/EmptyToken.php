<?php
namespace Leno\View\Token;

class EmptyToken extends \Leno\View\Token
{
    protected $reg = '/\<empty.*\>/U';

    public function result($line)
    {
        $var = $this->attrValue('name', $line);
        $tmp = '<?php if(empty(%s)) { ?>'."\n";
        return sprintf($tmp, $this->varString($var));
    }
}
