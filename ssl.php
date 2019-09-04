<?php
$load=5;
$GLOBALS['配置']['jm']=5;
include_once(dirname(__FILE__).'/lib/conf.php');
need_login();


$btda=jd(BT_SEND('获取SSL状态及证书信息', array('siteName'=>$user['domain'],)));
//die(print_r($btda));
//die(print_r([$btda,$btdsid,$btdssql]));

$data['ssli']=$btda;


if(!empty(R('qzsslstart'))){
	$ret=jd(BT_SEND('开启强制HTTPS', array('siteName'=>$user['name'],)));
	//print_r($ret);die();
	if(!$ret['status'])  redirect('?emsg='.urlencode($ret['msg']));
	redirect('?smsg='.urlencode($ret['msg'])); 
	
}
if(!empty(R('qzsslstop'))){
	$ret=jd(BT_SEND('关闭强制HTTPS', array('siteName'=>$user['name'],)));
	//print_r($ret);die();
	if(!$ret['status'])  redirect('?emsg='.urlencode($ret['msg']));
	redirect('?smsg='.urlencode($ret['msg']));
	
}
if(!empty(R('sslstop'))){
	$ret=jd(BT_SEND('关闭SSL', array('updateOf'=>'1','siteName'=>$user['domain'],)));
	//print_r($ret);die();
	if(!$ret['status'])  redirect('?emsg='.urlencode($ret['msg']));
	redirect('?smsg='.urlencode($ret['msg']));
}

if(!empty(R('add'))){
	
	if(empty(R('key'))) redirect('?emsg='.urlencode('设置失败'));  
	if(empty(R('csr'))) redirect('?emsg='.urlencode('设置失败'));  
	$ret=jd(BT_SEND('设置SSL域名证书', array('type'=>'1','siteName'=>$user['domain'],'key'=>R('key'),'csr'=>R('csr'))));
	//print_r($ret);die();
	if(!$ret['status'])  redirect('?emsg='.urlencode($ret['msg']));
	redirect('?smsg='.urlencode($ret['msg']));
}



$data['smsg']=$_GET['smsg'];
$data['emsg']=$_GET['emsg'];
//print_r($btda);


load_view('head',$data);
load_view('ssl',$data);
load_view('foot');