<?php
namespace Leno\View\Token;

class Fragment extends \Leno\View\Token
{
    protected $reg = '/\<fragment.*?\/\>/U';

    protected function replaceMatched($matched) : string
    {
        $name = $this->attrValue('name', $matched);
        return '<?php $this->getFragment(\''.$name.'\')->display(); ?>';
    }
}
