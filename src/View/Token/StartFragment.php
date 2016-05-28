<?php
namespace Leno\View\Token;

class StartFragment extends \Leno\View\Token
{
    protected $reg ='/\<fragment.*\>/U';

    protected function replaceMatched($matched) : string
    {
        $name = $this->attrValue('name', $matched);
        $type = $this->attrValue('type', $matched);
        if(empty($type)) {
            $type = \Leno\View::TYPE_REPLACE;
        }
        return '<?php $this->startFragment(\''.$name.'\', \''.$type.'\'); ?>';
    }
}
