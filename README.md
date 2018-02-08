#代码规范

> MySQL的表名需小写或小写加下划线，如：test, test_orders。
> 模块名（Model）需用大驼峰命名法，即首字母大写，并在名称后添加Model，如：TestModel。
> 控制器（Controller）需用大驼峰命名法，即首字母大写，并在名称后添加Controller，如：TestController，IndexController。
> 方法名（Action）需用小驼峰命名法，即首字母小写，如：index，indexPost。避免与私有方法冲突，请勿使用"_"开头，如 _test。