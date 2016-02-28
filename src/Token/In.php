<?php
namespace Leno\View\Token;

class In extends \Leno\View\Token
{
    protected $reg = '/\<in\s.*\>/U';

    public function result($line)
    {
        $var = $this->attrValue('name', $line);
        $value = $this->attrValue('value', $line);
        $tmp = '<?php if(in_array(%s, %s)) { ?>' . "\n";
        return sprintf($tmp, $var, $this->varString($value));
    }
}
