<?php
namespace Leno\View\Token;

class Func extends \Leno\View\Token
{
    protected $reg = '/\{\:.*\}/';

    protected function replaceMatched($matched) : string
    {
        var_dump($matched);
        return 'echo '.$this->funcString(preg_replace('/\{|\}|\:/', '', $matched)) . ';';
    }
}
