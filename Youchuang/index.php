<?php
/*
 * 路由：不同的请求分发路径
 *
 **/
//var_dump($_SERVER);
define("MVC","yes");
define("ROOT_URL",__DIR__);  //当前运行项目的文件夹地址。
define("LIBS_URL",ROOT_URL."/libs"); //libs的地址
define("TPL_INDEX_URL",ROOT_URL."/template/index"); //template前台
define("TPL_ADMIN_URL",ROOT_URL."/template/admin"); //template后台
define("HTTP_URL","http://".$_SERVER["SERVER_NAME"].substr($_SERVER["SCRIPT_NAME"],0,strrpos($_SERVER["SCRIPT_NAME"],"/"))); //http运行项目文件夹地址
define("FILE_URL","http://".$_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"]); //当前运行文夹的地址.
define("CSS_PATH",HTTP_URL."/static/css/"); //css文建夹地址。
define("JS_PATH",HTTP_URL."/static/js/");  //js文件夹地址。
define("IMG_PATH",HTTP_URL."/static/img/"); //img文件夹地址.
define("FONT_PATH",ROOT_URL."/static/font/");//字体路径。
include LIBS_URL."/db.class.php"; //include:只能包含本地文件。
include LIBS_URL."/session.class.php";  //引入核心类。
include LIBS_URL."/route.class.php";
include LIBS_URL."/main.class.php";
require LIBS_URL."/pages.class.php";
require LIBS_URL."/smarty/Smarty.class.php";
include LIBS_URL."/code.class.php";
include LIBS_URL."/functions.php";
include LIBS_URL."/indexMain.class.php";
include LIBS_URL."/Uploader.class.php";
$obj=new route();
$obj->init();

