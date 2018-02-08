<?php

/**
 * PX Content Management System
 * http://pxcms.yangfei.name
 * @copyright  Copyright (c) 2017 Anuny 
 * @license    pxcms is opensource software licensed under the MIT license.
 */
 
define('APP', true);

// 载入框架
require('sys/app.class.php');

// 运行系统
App::Instance()->BootStrap(); 
