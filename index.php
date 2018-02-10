<?php

/**
 * PX Content Management System
 * http://pxcms.yangfei.name
 * @copyright  Copyright (c) 2017 Anuny 
 * @license    pxcms is opensource software licensed under the MIT license.
 */
 
define('PX', true);

// 载入框架
require('sys/boot.php');

// 运行系统
sys\px\app::init()->bootStrap();