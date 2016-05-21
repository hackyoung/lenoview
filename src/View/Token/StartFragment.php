<?php
namespace Leno\View\Token;

class StartFragment extends \Leno\View\Token
{
    protected $reg ='/\<fragment.*?\>/U';

    protected function replaceMatched($matched) : string
    {
        $name = $this->attrValue('name', $matched);
        return '<?php $this->startFragment(\''.$name.'\'); ?>';
    }
}
