# lenoview
lenoview 是一个支持继承，组合，分支逻辑，条件判断的简单的PHP模板引擎，其核心代码不超过500行，四个类。方便拓展其功能
##安装
```pash
	composer require hackyoung/lenoview
```

##基本用法
我们假设其项目根目录为web,所有的View文件放在web/view,编译之后的模板文件放在web/tmp,首先我们需要编写简单的代码来设置View
test.php
```php
use \Leno\View\View;
use \Leno\View\Template;

View::addViewDir(web/view);
Template::setCacheDir(web/tmp);

$view = new View('child');
$view->display();
```
然后再写模板文件, 

father.lpt.php
```php
<view name="header" />
<h1>这是父模板，父模板包涵一个外部view定义的头</h1>
<fragment name="childImplement" />
```

child.lpt.php
```php
<extend name="father">
	<fragment name="childImplement">
		这是子类实现父类定义的fragment
	</fragment>
</extend>
```

header.lpt.php
```php
<div class="header">
	这是外部定义的头
</div>
```
