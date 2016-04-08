<?php
namespace Leno\View\Token;

class Nin extends \Leno\View\Token
{
    protected $reg = '/\<nin.*\>/U';

    public function result($line)
    {
        $var = $this->attrValue('name', $line);
        $value = $this->attrValue('value', $line);
        $tmp = '<?php if(!in_array(%s, %s)) { ?>' . "\n";
        return sprintf($tmp, $var, $this->varString($value));
    }
}
