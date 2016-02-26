<?php
namespace Leno\View;
use Leno\App;
use Leno\LObject;
use Leno\Exception\ViewException;
use Leno\LIF\ViewInterface;
use Leno\View\Template;

App::uses('LObject', 'Leno');
App::uses('ViewException', 'Leno.Exception');
App::uses('Template', 'Leno.View');
App::uses('ViewInterface', 'Leno.LIF');

/*
 * @name View
 * @description Leno的视图功能类
 */
class View extends LObject {

	// 模板的后缀
	const suffix = '.lpt.php';

    public static $dir;

	// 可在模板中访问的数据
	public $data = array();

	// 模板文件名
	private $file;

	// 用于继承的临时view名字
	private $_temp_name;

	// 解析模板到View文件的对象
	private $template;

	// 子View列表
	private $_view = array();

    // 继承时需要
    private $parent;

    protected $fragments = [];

    public function __construct($view, $data=array()) 
    {
        $this->file = $this->setFile($file);
		$this->data = $data;
		$this->template = new Template($this);
	}


    public function setFragment($name, $content) 
    {
        $this->fragments[$name] = new Fragment($content);
    }

    public function hasFragment($name) {
        return isset($this->fragments[$name]);
    }

    public function getFragment($name) {
        if(!$this->hasFragment($name)) {
            throw new \Exception(
                sprintf('fragment %s not found', $name)
            );
        }
        return $this->fragments[$name];
    }

    public function display() 
    {
        if($this->parent instanceof self) {
            foreach($this->fragments as $name=>$fragment) {
                if($this->parent->hasFragment($name)) {
                    $this->parent->setFragment($name, $fragment);
                }
            }
            $this->parent->display();
        } else {
            if(gettype($this->data) === 'array') {
                extract($this->data);
            }
            $this->handleFragment();
            include $this->template->display();
        }
	}

	/*
	 * @name set
	 * @description 设置View显示的变量,self::set('hello', 'world'),则可以在模板文件中通过{$hello}访问其hello的值,支持<?php echo $hello; ?>
	 * @param string index 变量名
	 * @param mixed value 变量值
	 */
	public function set($index, $value) {
		$this->data[$index] = $value;
	}

	public function getFile() {
		return $this->file;
	}

	/*
	 * @name start
	 * @description 父View定义的child标签的开始实现标记
	 * @param string name child标签的name属性
	 */
	public function startFragment($name) {
		ob_start();
		$this->_temp_name = $name;
	}

	/*
	 * @name end
	 * @description 取实现child标签的内容，然后赋值
	 */
	public function endFragment() {
		$name = $this->_temp_name;
        $this->setFragment($name, ob_get_contents());
		ob_end_clean();
	}

	/*
	 * @name e
	 * @description 获得子View的对象
	 * @param string idx 子View的索引名
	 */
	public function e($idx) {
		return $this->_view[$idx];
	}

	/*
	 * @name view
	 * @description 载入子View
	 * @param string idx 子View的访问索引
	 * @param View view 子View对象
	 */
	public function view($idx, $view, $data=false) {
		if($data) {
			foreach($this->data as $k=>$v) {
				$view->set($k,$v);
			}
		}
		$this->_view[$idx] = $view;
	}

	/*
	 * @name extend
	 * @description 继承一个View,可以实现所有的echo $var中的var
	 */
	public function extend($file, $show=true) {
        $this->parent = new View($file, $this->data);
	}

	public function __toString() {
        $this->display();
	}

    protected function setFile($view) 
    {
        $last = str_replace(".", "/", $view);
        foreach(self::$dir as $dir=>$v) {
            $file = preg_replace('/\/$/', '', $dir) . '/' . $last;
            if(is_file($file)) {
                return $file;
            }
        }
        throw new InvalidArgumentException(sprintf("%s is not exists", $file));
    }

    public static function addViewDir($dir) 
    {
        if(!is_dir($dir)) {
            throw new InvalidArgumentException(sprintf("%s is not a directory", $dir));
        }
        if(in_array(self::$dir, $dir)) {
            return;
        }
        self::$dir[] = $dir;
    }

    public static function deleteViewDir($dir) {
        $keys = array_keys(self::$dir, $dir);
        array_splice(self::$dir, $keys[0], 1);
    }
}
?>
