<?php
namespace Leno\View\Token;

class ExtendEnd extends \Leno\View\Token
{
    protected $reg = '/\<\/extend.*\>/U';

    public function result($line)
    {
		return '<?php $this->parent->display(); ?>'."\n";
    }
}
