<?php

/**
 * PX Content Management System
 * http://pxcms.yangfei.name
 * @copyright  Copyright (c) 2017 Anuny
 * @license opensource software licensed under the MIT license.
 */
 
// 入口标识
define('PX', true);

// 载入框架
require('sys/app.php');

// 运行系统
sys\app::init()->bootStrap();