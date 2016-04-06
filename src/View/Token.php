<?php
namespace Leno\View;

abstract class Token 
{
    protected $reg;

    abstract public function result($line);

    public function getRegExp()
    {
        return $this->reg;
    }

    protected function attrValue($name, $line) 
    {
        preg_match(
            '/\s+'.$name.'\=[\'\"].{1,}[\'\"]/U',
            $line, $attrarr
        );
        if(!isset($attrarr[0])) {
            return '';
        }
        $att = preg_replace('/'.$name.'=/', '', $attrarr[0]);
        return preg_replace('/[\'\"\s]/', '', $att);
    }

    protected function varString($var) 
    {
        $vararr = explode('.', $var);
        $v = '$'.$vararr[0];
        array_splice($vararr, 0, 1);
        foreach($vararr as $val) {
            $v .= '["'.$val.'"]';
        }
        return $v;
    }

    protected function normalEnd() 
    {
        return '<?php } ?>' . "\n";
    }

    protected function condition($cond) 
    {
        return sprintf('<?php if(%s) { ?>'."\n", $cond);
    }
}
