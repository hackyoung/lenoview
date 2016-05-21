<?php
namespace Leno\View\Token;

class View extends \Leno\View\Token
{
    protected $reg = '/\<view.*\>/';

    protected function replaceMatched($matched) : string
    {
        $name = $this->attrValue('name', $matched);
        $data = $this->attrValue('data', $matched);
        $extend_data = $this->attrValue('extend_data', $matched);

        $ret = '<?php $this->view(\'v\', new \Leno\View(\''.$name.'\'';
        if(!empty($data)) {
            $ret .= ', '.$this->right($data);
        }
        $ret .= ')';
        if($extend_data == 'true') {
            $ret .= ', true';
        }
        $ret .= '); ';
        $ret .= '$this->e("v")->display(); ?>';
        return $ret;
    }
}
