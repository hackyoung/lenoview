<?php
namespace Leno\View\Token;

class Fragment extends \Leno\View\Token
{
    protected $reg = '/\<fragment.*?\/\>/U';

    public function result($line)
    {
        $name = $this->attrValue('name', $line);
        return '<?php $this->getFragment(\''.$name.'\')->display(); ?>'."\n";
    }
}
