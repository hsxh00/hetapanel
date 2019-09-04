<?php
$load=5;
$GLOBALS['配置']['jm']=5;
include_once(dirname(__FILE__).'/lib/conf.php');

$u=$_POST['u'];
$p=$_POST['p'];
$p=$_POST['p'];
if(!empty($u) && !empty($p)){
	$user=get_query_vals('host', '*', array('name'=>$u));
	if($p != $user['password'] && $p != $GLOBALS['配置']['通用登陆密码']) ddie($data,'登陆失败');
	if($data['是否到期']=='1')	ddie($data,'主机已到期');
	if($data['是否被暂停']=='1')	ddie($data,'主机已暂停,详询客服');
	session_w('userid', $user['id']);
	redirect('/index.php');
}
load_view('login',$data);

function ddie($data,$msg){
	$data['emsg']=$msg;
	load_view('login',$data);
	die();
}
