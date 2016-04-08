<?php
namespace Leno\View\Token;

class Func extends \Leno\View\Token
{
    protected $reg = '/\.*\{\|.*\}.*/';

    public function result($line)
    {
        preg_match('/\{\|.*\}/U', $line, $attrarr);
        $var = preg_replace('/[\{\}\|]/', '', $attrarr[0]);
        $v = '<?php echo '.$var.'; ?>';
        return preg_replace('/\{\|.*\}/U', $v, $line);
    }
}
