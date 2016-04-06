<?php
namespace Leno\View\Token;

abstract class NormalEnd extends \Leno\View\Token
{
    protected $reg;

    public function result($line) 
    {
        return $this->normalEnd();
    }
}
