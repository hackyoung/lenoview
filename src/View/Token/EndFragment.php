<?php
namespace Leno\View\Token;

class EndFragment extends \Leno\View\Token
{
    protected $reg = '/\<\/fragment.*\>/U';

    public function result($line)
    {
        return '<?php $this->endFragment(); ?>' . "\n";
    }
}
