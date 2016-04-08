<?php
namespace Leno\View\Token;

class EmptyEnd extends NormalEnd
{
    protected $reg = '/\<\/empty.*\>/U';
}
