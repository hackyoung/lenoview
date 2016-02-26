<?php
namespace Leno\View;

App::uses('Token', 'Leno.View');
App::uses('Cacher', 'Leno');
App::uses('View', 'Leno.View');
/*
 * @name LTemplate
 * @description 模板
 */
class Template {

	const suffix = '.cache.php';

	protected $viewfile;

	protected $tokens = null; 

	protected $extend_stack = array();

	public function __construct($view) {

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
					return "\n";
				},
				'attrs'=>[]
			],
			'fragment'=> [
				'reg'=>'/\<fragment.*?\>/U',
				'callback'=>function($token, $line) {
					$name = $token->attr_value('name', $line);
					return '<?php echo $this->startFragment(\''.$name.'\'); ?>'."\n";
				},
				'attrs'=>['name']
			],
            'fragment_end'=> [
				'reg'=>'/\<\/fragment_end\>/U',
				'callback'=>function($token, $line) {
					return '<?php $this->endFragment(); ?>'."\n";
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
	}

	/*
	 * @name pass1
	 * @description 编译模板需要执行两遍，这是第一遍，其作用是将所有需要解析的标签放在一行，第二遍仅仅替换一行的标签即可,目前第一遍未实现，所以用户必须保证所有待解析的标签在一行
	 */
	public function pass1() {
		$file = $this->view->getFile();
		$content = file_get_contents($file);
		return $content;
	}

	public function dispatch($line) {
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

	/*
	 * @name pass2
	 * @description 编译模板的第二遍，替换标签生成
	 */
	public function pass2() {
		$file = $this->cacher->getFile(); 
		$fp = fopen($file, 'r');
		$content = '';
		while($line = fgets($fp)) {
			$this->state = false;
			$content .= $this->dispatch($line);
		}
		fclose($fp);
		return $content;
	}

	public function cache($content) {
        $file = self::$CACHEDIR . '/' .md5($this->view->getFile()) . self::suffix;
        file_put_contents($file, $content);
	}

	public function display() {
		$viewfile = $this->view;
		$cache = $this->cacher->getFile();
		if(!is_file($cache) || filemtime($cache) <= filemtime($viewfile)) {
			$content = $this->pass1();
			$this->cache($content);
			$content = $this->pass2();
			$this->cache($content);
		}
		return $cache;
	}

	public function __toString() {
		$cache = $this->display();
		return file_get_contents($cache);
	}

    public static function setCacheDir($dir) {
        self::$CACHEDIR = $dir;
    }
}
?>
