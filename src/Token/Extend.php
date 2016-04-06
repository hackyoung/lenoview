<?php
namespace Leno\View\Token;

class Extend extends \Leno\View\Token
{
    protected $reg = '/\<extend.*\>/U';

    public function result($line)
    {
        $name = $this->attrValue('name', $line);
        return '<?php $this->extend(\''.$name.'\'); ?>'."\n";
    }
}
