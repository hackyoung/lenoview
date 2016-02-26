<?php

use \Leno\View;
use \Leno\Template;

include dirname(dirname(__FILE__)) . '/vendor/autoload.php';

Template::setCacheDir(dirname(__FILE__));
View::addViewDir(dirname(__FILE__));

$view = new View("father");
