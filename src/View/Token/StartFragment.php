<?php
namespace Leno\View\Token;

class StartFragment extends \Leno\View\Token
{
    protected $reg ='/\<fragment.*?\>/U';

    public function result($line)
    {
        $name = $this->attrValue('name', $line);
        return '<?php $this->startFragment(\''.$name.'\'); ?>'."\n";
    }
}
