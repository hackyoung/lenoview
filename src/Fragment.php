<?php
namespace Leno\View;

class Fragment 
{
    private $content;

    public function __construct($content)  
    {
        $this->content = $content;
    }

    public function display() 
    {
        echo $content;
    }

    public function __toString() {
        return $content;
    }
}
