<?php
$load=5;
$GLOBALS['配置']['jm']=5;
include_once(dirname(__FILE__).'/lib/conf.php');
need_login();


$btda=jd(BT_SEND('获取网站三项配置开关', array('id'=>$user['siteid'],'path'=>$user['path'],)));
//die(print_r($btda));
//die(print_r([$btda,$btdsid,$btdssql]));
//print_r($btda);
$data['inf']=$btda;


if(!empty(R('stop'))){
	$ret=jd(BT_SEND('关闭密码访问网站', array('id'=>$user['siteid'],)));
	//print_r($ret);die();
	if(!$ret['status'])  redirect('?emsg='.urlencode($ret['msg']));
	redirect('?smsg='.urlencode($ret['msg']));
	
}


if(!empty(R('sub'))){
	
	if(empty(R('u'))) redirect('?emsg='.urlencode('设置失败'));  
	if(empty(R('p'))) redirect('?emsg='.urlencode('设置失败'));  
	$ret=jd(BT_SEND('设置密码访问网站', array('id'=>$user['siteid'],'username'=>R('u'),'password'=>R('p'),)));
	//print_r($ret);die();
	if(!$ret['status'])  redirect('?emsg='.urlencode($ret['msg']));
	redirect('?smsg='.urlencode($ret['msg']));
}



$data['smsg']=$_GET['smsg'];
$data['emsg']=$_GET['emsg'];
//print_r($btda);


load_view('head',$data);
load_view('pass',$data);
load_view('foot');