<?php
namespace Leno\View\Token;

class View extends \Leno\View\Token
{
    protected $reg = '/\<view.*\>/';

    public function result($line)
    {
        $name = $this->attrValue('name', $line);
        $data = $this->attrValue('data', $line);
        $extend_data = $this->attrValue('extend_data', $line);

        $ret = '<?php $this->view("v", new \Leno\View\View("'.$name.'"';
        if(!empty($data)) {
            $ret .= ', '.$this->varString($data);
        }
        $ret .= ')';
        if($extend_data == 'true') {
            $ret .= ', true';
        }
        $ret .= ') ?>'."\n";
        $ret .= '<?php $this->e("v")->display(); ?>'."\n";
        return $ret;
    }
}
