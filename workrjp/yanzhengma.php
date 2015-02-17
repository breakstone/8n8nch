<?php
require_once '_config/config.php';
require_once '_includes/validate.php';
$code=new ValidationCode(80, 20, 4);
$code->showImage();   //输出到页面中供 注册或登录使用
$_SESSION["bangvcode"]=$code->getCheckCode();//将验证码保存到服务器中

?>