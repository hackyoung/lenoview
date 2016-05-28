<?php
namespace Leno\View\Token;

class StaticMethod extends \Leno\View\Token
{
    protected $reg = '/\{\|.*\}/';

    protected function replaceMatched($matched) : string
    {
        return '<?php echo ('.$this->right($matched) . ' ?? \'\'); ?>';
    }
}
