<?php
namespace Leno\View\Token;

class NotEmpty extends \Leno\View\Token
{
    protected $reg = '/\<notempty.*\>/U';

    public function result($line)
    {
        $var = $this->attrValue('name', $line);
        $tmp = '<?php if(!empty(%s)) { ?>'."\n";
        return sprintf($tmp, $this->varString($var));
    }
}
