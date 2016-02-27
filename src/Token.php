<?php
namespace Leno\View;

class Token {

	private $reg;

	private $callback;

	protected $attrs;

	public $template;

	public function __construct($reg, $callback, $attrs=null) 
	{
		$this->reg = $reg;
		$this->callback = $callback;
		$this->attrs = $attrs;
	}

	public function setTemplate($tmp) 
	{
		$this->template = $tmp;
	}

	public function result($line) 
	{
		return call_user_func_array($this->callback, [$this, $line]);
	}

	public function attr_value($name, $line) 
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

	public function var_string($var) 
	{
		$vararr = explode('.', $var);
		$v = '$'.$vararr[0];
		array_splice($vararr, 0, 1);
		foreach($vararr as $val) {
			$v .= '["'.$val.'"]';
		}
		return $v;
	}

	public function normal_end() 
	{
		return '<?php } ?>' . "\n";
	}

	public function cond($cond) 
	{
		return sprintf('<?php if(%s) { ?>'."\n", $cond);
	}
}
