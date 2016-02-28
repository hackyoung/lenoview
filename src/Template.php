<?php
namespace Leno\View;

/**
 * View的模板处理类，一个View对应一个模板文件
 * Template对象负责将模板文件编译成PHP可执行
 * 的样子
 */
class Template 
{

    /**
     * 编译之后的文件后缀
     */
	const SUFFIX = '.cache.php';

	private static $cachedir;

	protected $cachefile;

    protected $view;

    protected $tokens = [];

    protected $tokenClasses = [
        'extend' => '\Leno\View\Token\Extend',
        'extendend' => '\Leno\View\Token\ExtendEnd',
        'getfragment' => '\Leno\View\Token\Fragment',
        'startfragment'=> '\Leno\View\Token\StartFragment',
        'endfragment' => '\Leno\View\Token\EndFragment',
        'llist' => '\Leno\View\Token\Llist',
        'llistend'=> '\Leno\View\Token\LlistEnd',
        'var' => '\Leno\View\Token\VarToken',
        'func' => '\Leno\View\Token\Func',
        'eq' => '\Leno\View\Token\Eq',
        'neq' => '\Leno\View\Token\Neq',
        'view' => '\Leno\View\Token\View',
        'in' => '\Leno\View\Token\In',
        'nin' => '\Leno\View\Token\Nin',
        'empty' => '\Leno\View\Token\EmptyToken',
        'notempty' => '\Leno\View\Token\NotEmpty',
        'inend' => '\Leno\View\Token\InEnd',
        'ninend' => '\Leno\View\Token\NinEnd',
        'eqend' => '\Leno\View\Token\EqEnd',
        'neqend' => '\Leno\View\Token\NeqEnd',
        'emptyend' => '\Leno\View\Token\EmptyEnd',
        'notemtyend' => '\Leno\View\Token\NotEmptyEnd',
    ]; 

	public function __construct($view) 
	{
		$this->view = $view;
		$file = $view->getFile();
		$this->cachefile = self::$cachedir . '/' .md5($this->view->getFile()) . self::SUFFIX;
        foreach($this->tokenClasses as $k=>$c) {
            $this->tokens[$k] = new $c;
        }
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
			if(preg_match($token->getRegExp(), $line)) {
				$this->stat = $stat;
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
