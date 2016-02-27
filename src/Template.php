<?php
namespace Leno\View;

class Template 
{

	const SUFFIX = '.cache.php';

	protected $viewfile;

	protected $tokens = null; 

	private static $cachedir;

	private $cachefile;

	public function __construct($view) 
	{

		$this->view = $view;
		$file = $view->getFile();
		$this->tokens = array(
			'extend' => [
				'reg'=> '/\<extend.*\>/U',
				'callback'=>function($token, $line) {
					$name = $token->attr_value('name', $line);
					return '<?php $this->extend(\''.$name.'\'); ?>'."\n";
				},
				'attrs'=>['name']
			],
			'extend_end' => [
				'reg'=> '/\<\/extend.*\>/U',
				'callback'=>function($token, $line) {
					return '<?php $this->parent->display(); ?>'."\n";
				},
				'attrs'=>[]
			],
			'getfragment' => [
				'reg'=>'/\<fragment.*?\/\>/U',
				'callback'=>function($token, $line) {
					$name = $token->attr_value('name', $line);
					return '<?php $this->getFragment(\''.$name.'\')->display(); ?>'."\n";
				},
				'attrs'=>['name']
			],
			'startfragment'=> [
				'reg'=>'/\<fragment.*?\>/U',
				'callback'=>function($token, $line) {
					$name = $token->attr_value('name', $line);
					return '<?php $this->startFragment(\''.$name.'\'); ?>'."\n";
				},
				'attrs'=>['name']
			],
            'endfragment'=> [
				'reg'=>'/\<\/fragment\>/U',
				'callback'=>function($token, $line) {
					$this->view->endFragment();
					return '<?php $this->endFragment(); ?>' . "\n";
				},
				'attrs'=>[]
            ],
			'llist'=>[
				'reg'=>'/\<llist.*\>/U',
				'callback'=>function($token, $line) {
					$name = $token->attr_value('name', $line);
					$id = $token->attr_value('id', $line);
					$varName = $token->var_string($name);
					$ret = '<?php if(gettype('.$varName.') != "array") { '
								.$varName.' = array(); } ?>'."\n";
					$ret .= '<?php foreach('.$varName.
							' as '.$token->var_string($id).') { ?>'."\n";
					return $ret;
				},
				'attrs'=>['name', 'id']
			],
			'llist_end'=>[
				'reg'=>'/\<\/llist.*\>/U',
				'callback'=>function($token, $line) {
					return $token->normal_end();
				},
				'attrs'=>[]
			],
			'var'=>[
				'reg'=>'/.*\{\$.*\}.*/', // 如{$hello.world.world}
				'callback'=>function($token, $line) {
					preg_match('/\{\$.*\}/U', $line, $attrarr);
					$var = preg_replace('/[\{\}\$]/', '', $attrarr[0]);
					$v = $token->var_string($var);
					$v = '<?php echo '.$v.'; ?>';
					return preg_replace('/\{\$.*\}/U', $v, $line);
				},
				'attrs'=>[]
			],
			'function'=>[
				'reg'=> '/\.*\{\|.*\}.*/', // 执行方法
				'callback'=>function($token, $line) {
					preg_match('/\{\|.*\}/U', $line, $attrarr);
					$var = preg_replace('/[\{\}\|]/', '', $attrarr[0]);
					$v = '<?php echo '.$var.'; ?>';
					return preg_replace('/\{\|.*\}/U', $v, $line);
				},
				'attrs'=>[]
			],
			'eq'=>[
				'reg'=> '/\<eq.*\>/U',
				'callback'=>function($token, $line) {
					$name = $token->attr_value('name', $line);
					$value = $token->attr_value('value', $line);
					return $token->cond($token->var_string($name) .'=='. $value);
				},
				'attrs'=>[]
			],
			'view'=>[
				'reg'=> '/\<view.*\>/',
				'callback'=>function($token, $line) {

					$name = $token->attr_value('name', $line);
					$data = $token->attr_value('data', $line);
					$extend_data = $token->attr_value('extend_data', $line);

					$ret = '<?php $this->view("v", new \Leno\View\View("'.$name.'"';
					if(!empty($data)) {
						$ret .= ', '.$token->var_string($data);
					}
					$ret .= ')';
					if($extend_data == 'true') {
						$ret .= ', true';
					}
					$ret .= ') ?>'."\n";
					$ret .= '<?php $this->e("v")->display(); ?>'."\n";
					return $ret;
				},
				'attrs'=>[]
			],
			'neq'=>[
				'reg'=>'/\<neq.*\>/U',
				'callback'=>function($token, $line) {
					$name = $token->attr_value('name', $line);
					$value = $token->attr_value('value', $line);
					return $token->cond($token->var_string($name) .'!='. $value);
				},
				'attrs'=>[]
			],
			'in'=>[
				'reg'=> '/\<in\s.*\>/U',
				'callback'=>function($token, $line) {
					$var = $token->attr_value('name', $line);
					$value = $token->attr_value('value', $line);
					$tmp = '<?php if(in_array(%s, %s)) { ?>' . "\n";
					return sprintf($tmp, $var, $token->var_string($value));
				},
				'attrs'=>[]
			],
			'empty'=>[
				'reg'=> '/\<empty.*\>/U',
				'callback'=>function($token, $line) {
					$var = $token->attr_value('name', $line);
					$tmp = '<?php if(empty(%s)) { ?>'."\n";
					return sprintf($tmp, $token->var_string($var));
				},
				'attrs'=>[]
			],
			't'=>[
				'reg'=> '/\<t.*\>/U',
				'callback'=>function($token, $line) {
					$t = $token->attr_value('value', $line);
					return preg_replace('/\<t.*\>/U', $t, $line);
				},
				'attrs'=>[]
			],
			'notempty'=>[
				'reg'=> '/\<notempty.*\>/U',
				'callback'=>function($token, $line) {
					$var = $token->attr_value('name', $line);
					$tmp = '<?php if(!empty(%s)) { ?>'."\n";
					return sprintf($tmp, $token->var_string($var));
				},
				'attrs'=>[]
			],
			'nin'=>[
				'reg'=> '/\<nin.*\>/U',
				'callback'=>function($token, $line) {
					$var = $token->attr_value('name', $line);
					$value = $token->attr_value('value', $line);
					$tmp = '<?php if(!in_array(%s, %s)) { ?>' . "\n";
					return sprintf($tmp, $var, $token->var_string($value));
				},
				'attrs'=>[]
			],
			'in_end'=>[
				'reg'=> '/\<\/in\s.*\>/U',
				'callback'=>function($token, $line) {
					return $token->normal_end();
				},
				'attrs'=>[]
			],
			'nin_end'=>[
				'reg'=> '/\<\/nin.*\>/U',
				'callback'=>function($token, $line) {
					return $token->normal_end();
				},
				'attrs'=>[]
			],
			'eq_end'=>[
				'reg'=> '/\<\/eq.*\>/U',
				'callback'=>function($token, $line) {
					return $token->normal_end();
				},
				'attrs'=>[]
			],
			'neq_end'=>[
				'reg'=> '/\<\/neq.*\>/U',
				'callback'=>function($token, $line) {
					return $token->normal_end();
				},
				'attrs'=>[]
			],
			'empty_end'=>[
				'reg'=> '/\<\/empty.*\>/U',
				'callback'=>function($token, $line) {
					return $token->normal_end();
				},
				'attrs'=>[]
			],
			'notempty_end'=>[
				'reg'=> '/\<\/notempty.*\>/U',
				'callback'=>function($token, $line) {
					return $token->normal_end();
				},
				'attrs'=>[]
			],
		);
		$this->cachefile = self::$cachedir . '/' .md5($this->view->getFile()) . self::SUFFIX;
	}

	public function pass1() 
	{
		$file = $this->view->getFile();
		$content = file_get_contents($file);
		$this->cache($content);
	}

	public function dispatch($line) 
	{
		foreach($this->tokens as $stat=>$token) {
			if(preg_match($token['reg'], $line)) {
				$this->stat = $stat;
				$token = new Token(
					$token['reg'], $token['callback'], $token['attrs']
				);
				return $token->result($line);
			}
		}
		return $line;
	}

	public function pass2() 
	{
		$file = $this->cachefile;
		$fp = fopen($file, 'r');
		$content = '';
		while($line = fgets($fp)) {
			$this->state = false;
			$content .= $this->dispatch($line);
		}
		fclose($fp);
		$this->cache($content);
	}

	public function cache($content) 
	{
		$file = $this->cachefile;
        file_put_contents($file, $content);
	}

	public function display() 
	{
		$viewfile = $this->view->getFile();
		$cache = $this->cachefile;
		if(!is_file($cache) || filemtime($cache) <= filemtime($viewfile)) {
			$content = $this->pass1();
			$content = $this->pass2();
		}
		return $cache;
	}

	public static function setCacheDir($dir) 
	{
        self::$cachedir = $dir;
    }
}
?>
