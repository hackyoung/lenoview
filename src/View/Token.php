<?php
namespace Leno\View;

abstract class Token 
{
    protected $reg;

    protected $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function replace() : string {
        $content = preg_replace_callback($this->reg, function($matches) {
            return $this->replaceMatched($matches[0]);
        }, $this->content);
        return $content;
    }

    abstract protected function replaceMatched($matched): string;

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

    protected function funcString($func) 
    {
        $vararr = explode('.', $func);
        if(count($vararr) == 1) {
            return $func;
        }
        $v = '$'.$vararr[0];
        array_splice($vararr, 0, 1);
        foreach($vararr as $val) {
            $v .= '->'.$val;
        }
        return $v;
    }

    protected function right($input)
    {
        if(preg_match('/^\$/', $input)) {
            return $this->varString(preg_replace('/^\$/', '', $input));
        }
        if (preg_match('/^\:/', $input)) {
            return $this->funcString(preg_replace('/^\:/', '', $input));
        }
        return '\''.$input.'\'';
    }

    protected function normalEnd() 
    {
        return '<?php } ?>';
    }

    protected function condition($cond) 
    {
        return sprintf('<?php if(%s) { ?>', $cond);
    }
}
