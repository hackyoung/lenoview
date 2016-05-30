<?php
namespace Leno\View\Token;

class Llist extends \Leno\View\Token
{
    protected $reg = '/\<llist.*\>/U';

    protected function replaceMatched($matched) : string
    {
        $name = $this->attrValue('name', $matched);
        $id = $this->attrValue('id', $matched);
        $var = $this->right($name);
        $ret = '<?php $__number__ = 0; %s = %s ?? []; foreach(%s as %s) { ?>';
        return sprintf($ret, $var, $var, $var, $this->varString($id));
    }
}
