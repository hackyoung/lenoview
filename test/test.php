<?php
use \Leno\View;

include dirname(dirname(__FILE__)) . '/vendor/autoload.php';

$template = View::getTemplateClass();
$template::setCacheDir(dirname(__FILE__));
View::addViewDir(dirname(__FILE__));

//$view = new View("child");
$view = new View('header');
$view->render();
