<?php

define('SLASH', DIRECTORY_SEPARATOR);
define('__ROOT__', dirname(__FILE__) . SLASH);
define('ROOT_URL', 'http:' . SLASH . SLASH . 'localhost:8080' . SLASH);
define('JS_PATH_URL', ROOT_URL . 'js' . SLASH);
define('JS_PATH', __ROOT__ . 'public' . SLASH . 'js' . SLASH);
define('CSS_PATH_URL', ROOT_URL . 'css' . SLASH);
define('CSS_PATH', __ROOT__ . 'public' . SLASH . 'css' . SLASH);
define('VIEW_PATH', __ROOT__ . 'views' . SLASH);

$_ = array_merge($_POST, $_GET);