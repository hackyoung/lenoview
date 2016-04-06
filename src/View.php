<?php
namespace Leno;

class View 
{

    /**
     * @var view 文件的后缀名
     */
    const SUFFIX = '.lpt.php';

    /**
     * @var view 的查找路径, 通过View::addViewDir(); 
     * View::deleteViewDir()两个方法来配置View的搜索路径,
     * View::addViewDir('test');
     * View::$dir = ['test'];
     * View::deleteViewDir('test');
     * View::$dir = [];
     */
    protected static $dir = [
        __DIR__ . '/Template',
    ];

    /**
     * @var array data View对象可以使用的数据,通过View::set方法来设置它
     */
    public $data = [
        '__head__' => [
            'title' => '',
            'keywords' => 'leno,hackyoung,view',
            'description' => 'a simple framework component',
            'author' => 'hackyoung@163.com',
            'js' => [],
            'css' => [],
        ]
    ];

    protected static $templateClass = '\Leno\View\Template';

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
        if(isset($data['__head__'])) {
            $head = array_merge($this->__head__, $data['__head__']);
        } else {
            $head = $this->__head__;
        }
        $data['__head__'] = $head;
        $this->data = array_merge($data);
        $this->template = self::newTemplate($this);
    }

    public function __toString() {
        return $this->display();
    }

    public function __get($key)
    {
        return $this->data[$key];
    }

    public function __set($key, $val)
    {
        $this->data[$key] = $val;
    }

    /**
     * 设置view对象的fragment
     * @param string $name 索引的名字
     * @param Fragment $fragment 一个fragment对象
     */
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

    /**
     * 判断该view是否有名为name的fragment
     * @param string $name 索引的fragment的名字
     * @return boolean
     */
    public function hasFragment($name) 
    {
        return isset($this->fragments[$name]);
    }

    /**
     * 通过name获取view有的一个fragment
     * @param string $name 索引的fragment名字
     */
    public function getFragment($name) 
    {
        if(!$this->hasFragment($name)) {
            throw new \Exception(
                sprintf('fragment %s not found', $name)
            );
        }
        return $this->fragments[$name];
    }

    /**
     * 在模板文件中使用，标记从该方法之后的内容为一个fragment的内容
     * @param string $name fragment的名字
     */
    protected function startFragment($name) 
    {
        $this->temp_name = $name;
        ob_start();
    }

    /**
     * 在模板文件中使用，标记一个fragment内容结束的地方
     */
    protected function endFragment() 
    {
        $name = $this->temp_name;
        if(empty($name)) {
            return;
        }
        $content = ob_get_contents();
        ob_end_clean();
        $this->setFragment($name, new \Leno\View\Fragment($content));
    }

    /**
     * 显示一个view
     */
    public function display() 
    {
        if(!$this->parent instanceof self && gettype($this->data) === 'array') {
            extract($this->data);
        }
        return include $this->template->display();
    }

    /**
     * 设置一个变量，在模板中使用
     * @param string $var 在模板中使用的变量名
     * @param mixed value 变量的值
     */
    public function set($var, $value) 
    {
        $this->data[$var] = $value;
    }

    /**
     * 获得该View的模板文件的绝对路径
     */
    public function getFile() 
    {
        return $this->file;
    }

    /**
     * 获得一个组合的子View
     * @param string $idx self::view定义的索引名
     * @return View 通过$idx返回的view对象
     */
    public function e($idx) 
    {
        return $this->view[$idx];
    }

    /**
     * 添加一个组合的子View
     * @param string $idx 用于索引的名字
     * @param View $view View对象
     * @param boolean $data 是否复制该View的data到子View
     */
    public function view($idx, $view, $data=false) 
    {
        if($data) {
            foreach($this->data as $k=>$v) {
                $view->set($k,$v);
            }
        }
        $this->view[$idx] = $view;
    }

    /**
     * 继承一个View，可以实现或者重写上一个View的fragment
     * @param string $file view的模板文件
     */
    public function extend($file) 
    {
        $this->parent = new View($file, $this->data);
        $this->parent->setChild($this);
    }

    /**
     * 设置孩子，仅仅在self::extend中执行才有效
     */
    public function setChild($child) 
    {
        if($child->parent->equal($this)) {
            $this->child = $child;
        }
    }

    /**
     * 判断当前view是否和所传view相等
     * @param View $view 待比较的view对象
     */
    public function equal($view)
    {
        return ($this->getFile() === $view->getFile());
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
        throw new \InvalidArgumentException(
            sprintf("%s is not exists", $file)
        );
    }

    public function addJs($js) {
        if(is_array($js)) {
            $this->data['__head__']['js'] = array_merge(
                $this->__head__['js'], $js
            );
        } else {
            $this->data['__head__']['js'][] = $js;
        }
    }

    public function addCss($css) {
        if(is_array($css)) {
            $this->data['__head__']['css'] = array_merge(
                $this->__head__['css'], $css
            );
        } else {
            $this->data['__head__']['css'][] = $js;
        }
    }

    public static function setTemplateClass($templateClass)
    {
        self::$templateClass = $templateClass;
    }

    public static function getTemplateClass()
    {
        return self::$templateClass;
    }

    public static function newTemplate(View $view)
    {
        return new self::$templateClass($view);
    }

    public static function addViewDir($dir) 
    {
        if(!is_dir($dir)) {
            return;
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
