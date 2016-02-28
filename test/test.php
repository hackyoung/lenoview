<?php

use \Leno\View\View;
use \Leno\View\Template;

include dirname(dirname(__FILE__)) . '/vendor/autoload.php';

Template::setCacheDir(dirname(__FILE__));
View::addViewDir(dirname(__FILE__));
$view = new View("schild", ['div'=>'hello', 'hello'=>[1,2,3,4,5]]);
$view->display();
