<?php
namespace Leno\View\Token;

class VarToken extends \Leno\View\Token
{
    protected $reg = '/.*\{\$.*\}.*/'; // 如{$hello.world.world}

    public function result($line)
    {
        preg_match('/\{\$.*\}/U', $line, $attrarr);
        $var = preg_replace('/[\{\}\$]/', '', $attrarr[0]);
        $v = $this->varString($var);
        $v = '<?php echo '.$v.'; ?>';
        return preg_replace('/\{\$.*\}/U', $v, $line);
    }
}
