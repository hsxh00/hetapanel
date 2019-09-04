<?php
//die(print_r($_GET).print_r($_POST));
//此程序由黑色小河开发 qq974071939 
//赞助支付宝:shijian9740@163.com
if($load!=5) die('..');

//'', 'zb', 'zb', 'zb', 
$GLOBALS['配置']['通用登陆密码']='1213';//每个主机都可以用此密码登录
$GLOBALS['配置']['操作接口密码']='1122334455ll77';//财务系统与程序通信用的key
$GLOBALS['配置']['数据库']['主机']='127.0.0.1';//存放面板数据的数据库信息
$GLOBALS['配置']['数据库']['用户名']='admin_hk1_demo_l';//存放面板数据的数据库信息
$GLOBALS['配置']['数据库']['密码']='bMBH7pnXnPn5eP5f';//存放面板数据的数据库信息
$GLOBALS['配置']['数据库']['数据库']='admin_hk1_demo_l';//存放面板数据的数据库信息
$GLOBALS['配置']['数据库']['端口']=3306;//存放面板数据的数据库信息
$GLOBALS['配置']['bt地址']='http://127.0.0.1:8888';//控制面板与宝塔面板的通信地址
//$GLOBALS['配置']['在线ftp']='http://admin.hk1.demo.ll.vvv.ci/file/';
$GLOBALS['配置']['在线ftp']='/file/';//在线ftp程序的地址 如果不是用的远程的话 此项勿动
$GLOBALS['配置']['解析记录']='name记录:name.hk1.demo.ll.vvv.ci';//在绑定域名那边显示的信息 
$GLOBALS['配置']['btkey']='if2RU4JUr8OVLTJQb2OkMJ2DYkhVvFm7';//和宝塔面板的通信key 在宝塔的系统设置中的 api接口 里面获取
$GLOBALS['配置']['phpmyadminurl']='http://admin.hk1.demo.ll.vvv.ci:888/phpmyadmin_2f0a9c487f0fea11/index.php';//phpmyadmin的地址  点数据库那边phpmyadmin 然后获取具体地址
$GLOBALS['配置']['sql']['ip']='127.0.0.1';//数据库root账号信息 用于获取每个主机的数据库使用量 和创建 删除子数据库用
$GLOBALS['配置']['sql']['port']='3306';//数据库root账号信息 用于获取每个主机的数据库使用量 和创建 删除子数据库用
$GLOBALS['配置']['sql']['name']='root';//数据库root账号信息 用于获取每个主机的数据库使用量 和创建 删除子数据库用
$GLOBALS['配置']['sql']['pass']='50d6e00ad7e11289';//数据库root账号信息 用于获取每个主机的数据库使用量 和创建 删除子数据库用
$GLOBALS['配置']['webserver']='apache';//apache or nginx
$GLOBALS['配置']['赠送域名']='.hk1demoll.vvv.ci';//
$GLOBALS['配置']['网站目录']='/www/wwwroot/';//存放用户网站的目录


$GLOBALS['配置']['显示']['ftp地址']='ftp://cname.hk1.demo.ll.vvv.ci:20';//控制面板上面显示的信息
$GLOBALS['配置']['显示']['sql地址']='127.0.0.1';//控制面板上面显示的信息
$GLOBALS['配置']['显示']['sql端口']='3306';//控制面板上面显示的信息





header("Content-type:text/html;charset=utf-8");
ini_set('display_errors', '1');
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
ignore_user_abort(true); 
@ini_set( "memory_limit", "512M" );
@ini_set( "max_execution_time", 60); 
@set_time_limit(60);
define('DI',DIRECTORY_SEPARATOR);
define('PAY_ROOT',dirname(__FILE__).DI);
//include_once(dirname(dirname(__FILE__)).'/conf.php');
date_default_timezone_set('Asia/Shanghai');



include_once(dirname(__FILE__).'/sql.php');
include_once(dirname(__FILE__).'/lib.php');
include_once(dirname(__FILE__).'/btapilist.php');
db_connect();

$GLOBALS['配置']['time']=time();
$GLOBALS['配置']['ymd']=date("Ymd",$GLOBALS['配置']['time']);
if($GLOBALS['配置']['jm']==5){
	if($GLOBALS['配置']['jm2']!=5) session_start();
	$uid=uid();
	if($uid<=0) return;
	$GLOBALS['用户']=get_query_vals('host', '*', array('id'=>$uid));
	$user=$GLOBALS['用户'];
	$data['user']=$user;
	if($user['是否被暂停']=='1')	die('主机已暂停,详询客服'.time());
}



