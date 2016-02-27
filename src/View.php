<?php
namespace Leno\View;

class View 
{

	/**
	 * @var view 文件的后缀名
	 */
	const SUFFIX = '.lpt.php';

	/**
	 * @var array data View对象可以使用的数据,通过View::set方法来设置它
	 */
	public $data = [];

	/**
	 * @var view 的查找路径, 通过View::addViewDir(); 
	 * View::deleteViewDir()两个方法来配置View的搜索路径,
	 * View::addViewDir('test');
	 * View::$dir = ['test'];
	 * View::deleteViewDir('test');
	 * View::$dir = [];
	 */
    protected static $dir = [];

	/**
	 * @var Template template 处理该模板文件的Template对象
	 */
	protected $template;

	/**
	 * @var array view 组合的View
	 */
	protected $view = [];

	/**
	 * @var View parent 该View的父亲View
	 */
    protected $parent;

	/**
	 * @var View child 继承该View的View对象
	 */
	protected $child;

	/**
	 * @var array 该View拥有的Fragment
	 */
    protected $fragments = [];

	/**
	 * @var string file View对象的模板文件的绝对路径文件
	 */
	private $file;

	/**
	 * @var string temp_name start/endFragment的时候用
	 */
	private $temp_name;

	/**
	 * 构造函数
	 * @param string $view 基于查找路径的view文件
	 * @param array $data 模板需要用的参数
	 */
    public function __construct($view, $data=[]) 
    {
        $this->file = $this->setFile($view);
		$this->data = $data;
		$this->template = new Template($this);
	}


    public function setFragment($name, $fragment) 
    {
		if($this->child instanceof self && $this->child->hasFragment($name)) {
			$fragment = $this->child->getFragment($name);
		}
    	$this->fragments[$name] = $fragment;
		if($this->parent instanceof self) {
			$this->parent->setFragment($name, $fragment);
		}
    }

	public function hasFragment($name) 
	{
        return isset($this->fragments[$name]);
    }

	public function getFragment($name) 
	{
        if(!$this->hasFragment($name)) {
            throw new \Exception(
                sprintf('fragment %s not found', $name)
            );
        }
        return $this->fragments[$name];
    }

	public function startFragment($name) 
	{
		$this->temp_name = $name;
		ob_start();
	}

	public function endFragment() 
	{
		$name = $this->temp_name;
		if(empty($name)) {
			return;
		}
		$content = ob_get_contents();
		ob_end_clean();
        $this->setFragment($name, new Fragment($content));
	}

    public function display() 
    {
		if(!$this->parent instanceof self && gettype($this->data) === 'array') {
			extract($this->data);
		}
		include $this->template->display();
	}

	public function set($index, $value) 
	{
		$this->data[$index] = $value;
	}

	public function getFile() 
	{
		return $this->file;
	}


	public function e($idx) 
	{
		return $this->view[$idx];
	}

	public function view($idx, $view, $data=false) 
	{
		if($data) {
			foreach($this->data as $k=>$v) {
				$view->set($k,$v);
			}
		}
		$this->view[$idx] = $view;
	}

	public function extend($file, $show=true) 
	{
        $this->parent = new View($file, $this->data);
		$this->parent->setChild($this);
	}

	public function setChild($child) {
		$this->child = $child;
	}

    protected function setFile($view) 
    {
        $last = str_replace(".", "/", $view) . self::SUFFIX;
        foreach(self::$dir as $dir) {
            $file = preg_replace('/\/$/', '', $dir) . '/' . $last;
            if(is_file($file)) {
                return $file;
            }
        }
        throw new \InvalidArgumentException(sprintf("%s is not exists", $file));
    }

    public static function addViewDir($dir) 
    {
        if(!is_dir($dir)) {
            throw new \InvalidArgumentException(sprintf("%s is not a directory", $dir));
        }
        if(in_array($dir, self::$dir)) {
            return;
        }
        self::$dir[] = $dir;
    }

	public static function deleteViewDir($dir) 
	{
        $keys = array_keys(self::$dir, $dir);
        array_splice(self::$dir, $keys[0], 1);
    }
}
?>
