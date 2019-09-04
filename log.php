<?php
$load=5;
$GLOBALS['配置']['jm']=5;
include_once(dirname(__FILE__).'/lib/conf.php');
need_login();


$btda=jd(BT_SEND('获取网站日志', array('siteName'=>$user['domain'],)));
//die(print_r($btda));
//die(print_r([$btda,$btdsid,$btdssql]));

$data['logtxt']=$btda['msg'];



$data['smsg']=$_GET['smsg'];
$data['emsg']=$_GET['emsg'];
//print_r($btda);


load_view('head',$data);
load_view('log',$data);
load_view('foot');