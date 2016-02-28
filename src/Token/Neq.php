<?php
namespace Leno\View\Token;

class Neq extends \Leno\View\Token
{
    protected $reg = '/\<neq.*\>/U';

    public function result($line)
    {
        $name = $this->attrValue('name', $line);
        $value = $this->attrValue('value', $line);
        $const = $this->attrValue('const', $line);
        if($const == 'true') {
            $value = '\''.$value.'\'';
        } else {
            $value = $this->varString($value);
        }
        return $this->condition($this->varString($name) .'!='. $value);
    }
}
