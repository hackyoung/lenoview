<?php
namespace Leno\View\Token;

class Llist extends \Leno\View\Token
{
    protected $reg = '/\<llist.*\>/U';

    public function result($line)
    {
        $name = $this->attrValue('name', $line);
        $id = $this->attrValue('id', $line);
        $varName = $this->varString($name);
        $ret = '<?php if(gettype(';
        $ret .= $varName;
        $ret .= ') !== "array") { ';
        $ret .= $varName . ' = []; } ?>'."\n";
        $ret .= '<?php foreach(';
        $ret .= $varName;
        $ret .=' as '.$this->varString($id);
        $ret .=') { ?>'."\n";
        return $ret;
    }
}
