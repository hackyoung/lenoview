<?php
namespace Leno\View;

class Fragment 
{
    private $content;

    private $child;

    public function __construct($content)  
    {
        $this->content = $content;
    }

    public function setChild($child)
    {
        $this->child = $child;
        return $this;
    }

    public function display() 
    {
        echo $this->getContent();
    }

    public function getContent()
    {
        $content = $this->content;
        if($this->child) {
            $fragment = $this->child['fragment'];
            $type = $this->child['type'];
            if($type === \Leno\View::TYPE_REPLACE) {
                $content = $fragment->getContent();
            } elseif ($type === \Leno\View::TYPE_AFTER) {
                $content .= $fragment->getContent();
            } elseif ($type === \Leno\View::TYPE_BEFORE) {
                $content = $fragment->getContent() . $content;
            }
        }
        return $content;
    }

    public function __toString() {
        return $this->content;
    }
}
